<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\RecipeCondimentRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ShoppingListController extends AbstractController
{
    // ① favorite/index.html.twig の「Créer la liste de courses」から呼ばれる
    // 選んだレシピIDだけを、セッションに保存する
    /**
     * Step 1: called from the "Créer la liste de courses" button on the
     * favorites page. Stores the selected recipe IDs in the session only
     * (no database write yet).
     */
    #[Route('/shopping-list/create', name: 'app_shopping_list_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, SessionInterface $session): Response
    {
        // フォームから送られてきた、recipe_idsという名前のデータを、配列として取り出す
        $recipeIds = $request->request->all('recipe_ids');
        // 「今受け取った、その数字の配列を、セッション（一時的な保管庫）に、覚えさせておく」という意味です。
        $session->set('shopping_list_recipe_ids', $recipeIds);

        return $this->redirectToRoute('app_shopping_list_new');
    }

    // ② shopping_list/new.html.twig の「Valider ma liste」から呼ばれる
    // チェック内容・メモを、セッションに保存する
    /**
     * Step 2: called from the "Valider ma liste" button. Stores the checked
     * items and the free-text memo in the session, then redirects to the
     * confirmation screen (Post/Redirect/Get pattern).
     */
    #[Route('/shopping-list/confirm', name: 'app_shopping_list_confirm', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function confirm(Request $request, SessionInterface $session): Response
    {
        // 各レシピの「含めるか」チェック状態を、まとめて受け取る
        // （include_recipe_1, include_recipe_23 のように、レシピIDごとに名前が違うので、
        //   $request->request->all() で、送られてきた"全部"を、一旦受け取る）
        $allData = $request->request->all();
        // これはメモがなかったら、空の配列として送ってください、という意味
        $memo = $request->request->get('memo', '');

        // セッションに、今回チェックされた内容・メモを保存する
        $session->set('shopping_confirm_allData', $allData);

        return $this->redirectToRoute('app_shopping_list_confirm_show');
    }

    // ③ セッションから、チェック内容・メモを取り出して、確認画面（Image 2相当）を表示する
    // US6.2 CA1：材料・調味料を、レシピごとにグループ分けして表示するため、
    // buildDisplayItems() という共通部品（下にあります）を使って、組み立てる
    /**
     * Step 3: reads the checked items and memo back from the session and
     * displays the confirmation screen, grouped by recipe via
     * buildDisplayItems() (US6.2 CA1).
     */
    #[Route('/shopping-list/confirm', name: 'app_shopping_list_confirm_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function confirmShow(
        SessionInterface $session,
        RecipeIngredientRepository $recipeIngredientRepository,
        RecipeCondimentRepository $recipeCondimentRepository
    ): Response {
        // セッションに保存しておいた、大きな配列（TWIGでINPUTにこのような名前がついているため）を、丸ごと取り出しています。
        $allData = $session->get('shopping_confirm_allData', []);
        // 「$allDataという配列の中に、'checked_items'というキーが、もし存在すれば、その値を使う。もし存在しなければ（nullのような扱いになるので）、代わりに、空の配列[]を使う」
        // この ?? があることで、エラーの予防になる
        $checkedItems = $allData['checked_items'] ?? [];
        $memo = $allData['memo'] ?? '';

        // 以前は、ここに長い foreach を、直接書いていたが、
        // buildDisplayItems() という部品にまとめたので、それを呼び出すだけで済む
        $displayItems = $this->buildDisplayItems($checkedItems, $recipeIngredientRepository, $recipeCondimentRepository);

        return $this->render('shopping_list/confirm.html.twig', [
            'displayItems' => $displayItems,
            'memo' => $memo,
        ]);
    }

    // ④ favorite/index.html.twig から選んだレシピの一覧を、人数調整UI付きで表示する画面
    /**
     * Display the recipes selected from the favorites page, with the
     * person-count adjustment UI. Fetches Favorite records (not just
     * Recipe) so the saved person count is available too.
     */
    #[Route('/shopping-list/new', name: 'app_shopping_list_new', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        SessionInterface $session,
        RecipeRepository $recipeRepository,
        FavoriteRepository $favoriteRepository
    ): Response {
        // セッションから、さっき保存しておいたレシピIDの配列を取り出す
        // 万が一セッションに何も無ければ（直接このURLにアクセスした場合など）、空配列をデフォルトにする
        $recipeIds = $session->get('shopping_list_recipe_ids', []);

        // Recipeだけでなく、Favorite（人数の情報を持っている）を、まとめて取得する
        // 「レシピのIDだけを追いかけるのではなく、"お気に入り"という、ユーザー・レシピ・人数がセットになった記録そのものを
        // 取ってくることで、人数の情報も自然に手に入る」という考え方
        $favorites = $favoriteRepository->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.recipe IN (:recipeIds)')
            ->setParameter('user', $this->getUser())
            ->setParameter('recipeIds', $recipeIds)
            ->getQuery()
            ->getResult();

        return $this->render('shopping_list/new.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    // ⑤ 「Envoyer」ボタンから呼ばれる、メール送信アクション
    // US6.2 CA3：入力されたメールアドレスへ、材料・調味料の一覧を、メールで送信する
    // US6.2 CA2：「Inclure la recette」がチェックされているレシピは、作り方も本文に含める
    /**
     * Final step: sends the shopping list by e-mail (US6.2 CA3), to either
     * the account's own address or a free-text address. Recipes checked as
     * "Inclure la recette" also get their preparation steps in the body
     * (US6.2 CA2). Nothing is persisted to the database; the e-mail itself
     * acts as the history.
     */
    #[Route('/shopping-list/send', name: 'app_shopping_list_send', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function send(
        Request $request,
        SessionInterface $session,
        MailerInterface $mailer,
        RecipeIngredientRepository $recipeIngredientRepository,
        RecipeCondimentRepository $recipeCondimentRepository,
        RecipeRepository $recipeRepository
    ): Response {
        $allData = $session->get('shopping_confirm_allData', []);
        $checkedItems = $allData['checked_items'] ?? [];

        // confirmShow() と同じ部品を使って、材料・調味料を、レシピごとにグループ分けする
        $displayItems = $this->buildDisplayItems($checkedItems, $recipeIngredientRepository, $recipeCondimentRepository);

        $emailChoice = $request->request->get('email_choice');

        // US6.2 CA3：メール送信先を、アカウントのメール、または、自由入力の別のメールから選ぶ
        if ($emailChoice === 'account') {
            $toAddress = $this->getUser()->getEmail();
        } else {
            $toAddress = $request->request->get('custom_email');
        }

        $bodyText = "Voici votre liste de courses :\n\n";

        // $recipeId => $group と書くことで、キー（レシピID）と、値（レシピ名・材料リスト）の、両方を受け取る
        foreach ($displayItems as $recipeId => $group) {
            $bodyText .= $group['recipeName'] . "\n";
            foreach ($group['items'] as $item) {
                $bodyText .= "- " . $item['name'] . " : " . $item['quantity'] . " " . $item['unit'] . "\n";
            }

            // もし「このレシピを含める」がチェックされていれば、作り方も、本文に追加する
            if (isset($allData['include_recipe_' . $recipeId])) {
                $recipe = $recipeRepository->find($recipeId);
                if ($recipe) {
                    $bodyText .= "\nstep : " . $recipe->getStep() . "\n";
                }
            }

            $bodyText .= "\n";
        }

        $email = (new Email())
            ->from('meikotoulouse0726@gmail.com')
            ->to($toAddress)
            ->subject('Votre liste de courses - Wasyoku Sensei')
            ->text($bodyText);

        $mailer->send($email);

        // US6.2 CA4：送信完了メッセージを、次の画面で、ポップアップとして表示するための、フラッシュメッセージ
        $this->addFlash('shopping_list_sent', true);

        return $this->redirectToRoute('app_shopping_list_confirm_show');
    }

    // confirmShow() と send() の、両方から呼ばれる、共通の部品
    // セッションに保存された checked_items（'ingredient_70' のような文字列の配列）から、
    // 実際の材料名・分量・単位を取得し、レシピIDごとにグループ分けした配列を組み立てる
    /**
     * Shared helper used by both confirmShow() and send().
     * Takes the session's checked_items (strings like "ingredient_70" or
     * "condiment_12"), resolves each one to its actual name/quantity/unit,
     * and groups the results by recipe. Factored out to avoid duplicating
     * this logic in two places and risking the two copies drifting apart.
     */
    private function buildDisplayItems(
        array $checkedItems,
        RecipeIngredientRepository $recipeIngredientRepository,
        RecipeCondimentRepository $recipeCondimentRepository
    ): array {
        $displayItems = [];

        foreach ($checkedItems as $item) {
            // $itemが、例えば'ingredient_70'だったとします。explode('_', ...)は、「_という記号の場所で、文字列を切り分けて、その結果を、配列として返す」関数
            $parts = explode('_', $item);

            // $partsという変数の中に、「切り分けられた、2つの文字列が入った、配列」が、入っています。$parts = ['ingredient', '70'];
            $type = $parts[0];

            // この分けられた配列の中のIDは1番目の順番にあるところだよと教えている
            $id = $parts[1];

            if ($type === 'ingredient') {
                $ri = $recipeIngredientRepository->find($id);
                if ($ri) {
                    $recipeId = $ri->getRecipe()->getId();
                    $recipeName = $ri->getRecipe()->getName();

                    // このレシピIDが、まだ $displayItems に登場していなければ、先に「箱」を作っておく。「からあげ」という引き出し自体が、まだ無ければ
                    if (!isset($displayItems[$recipeId])) {
                        // その引き出し自体を、新しく作る（中には、レシピ名と、まだ空っぽの材料リストを入れる）
                        $displayItems[$recipeId] = [
                            'recipeName' => $recipeName,
                            'items' => [],
                        ];
                    }

                    // 「からあげ」の引き出しの中の、「材料リスト」という、さらに小さい引き出しに、今処理している1つの材料を、追加する
                    $displayItems[$recipeId]['items'][] = [
                        'name' => $ri->getIngredient()->getName(),
                        'quantity' => $ri->getQuantity(),
                        'unit' => $ri->getUnit(),
                    ];
                }
            } elseif ($type === 'condiment') {
                $rc = $recipeCondimentRepository->find($id);
                if ($rc) {
                    $recipeId = $rc->getRecipe()->getId();
                    $recipeName = $rc->getRecipe()->getName();

                    if (!isset($displayItems[$recipeId])) {
                        $displayItems[$recipeId] = [
                            'recipeName' => $recipeName,
                            'items' => [],
                        ];
                    }

                    $displayItems[$recipeId]['items'][] = [
                        'name' => $rc->getCondiment()->getName(),
                        'quantity' => $rc->getQuantity(),
                        'unit' => $rc->getUnit(),
                    ];
                }
            }
        }

        return $displayItems;
    }
}