<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeCondimentRepository;

final class ShoppingListController extends AbstractController
{

    // ① favorite/index.html.twig の「Créer la liste de courses」から呼ばれる
    // 選んだレシピIDだけを、セッションに保存する
    #[Route('/shopping-list/create', name: 'app_shopping_list_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, SessionInterface $session): Response
    { // フォームから送られてきた、recipe_idsという名前のデータを、配列として取り出す
        $recipeIds = $request->request->all('recipe_ids');
        // 「今受け取った、その数字の配列を、セッション（一時的な保管庫）に、覚えさせておく」**という意味です。
        $session->set('shopping_list_recipe_ids', $recipeIds);


        return $this->redirectToRoute('app_shopping_list_new');
    }


    // ② shopping_list/new.html.twig の「Valider ma liste」から呼ばれる
    // チェック内容・メモを、セッションに保存する
    #[Route('/shopping-list/confirm', name: 'app_shopping_list_confirm', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function confirm(Request $request, SessionInterface $session): Response
    {

        // 各レシピの「含めるか」チェック状態を、まとめて受け取る
        // （include_recipe_1, include_recipe_23 のように、レシピIDごとに名前が違うので、
        //   $request->request->all() で、送られてきた"全部"を、一旦受け取る）
        $allData = $request->request->all();
        $memo = $request->request->get('memo', '');
        // セッションに、今回チェックされた内容・メモを保存する
        $session->set('shopping_confirm_allData', $allData);
        $session->set('shopping_confirm_memo', $memo);
        // ''これはメモがなかったらからの配列として送ってくださいといういみ

        return $this->redirectToRoute('app_shopping_list_confirm_show');
    }


   // ③ セッションから、チェック内容・メモを取り出して、確認画面（Image 2相当）を表示する
    // US6.2 CA1：材料・調味料を、レシピごとにグループ分けして表示するため、
    // Recipeではなく RecipeIngredient / RecipeCondiment を、IDから直接検索している
    #[Route('/shopping-list/confirm', name: 'app_shopping_list_confirm_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function confirmShow(
        SessionInterface $session,
        RecipeIngredientRepository $recipeIngredientRepository,
        RecipeCondimentRepository $recipeCondimentRepository
    ): Response {
        // セッションに保存しておいた、大きな配列（Twigのinputにこの名前を付けていたため）を、丸ごと取り出す
        $allData = $session->get('shopping_confirm_allData', []);

        // 「$allDataという配列の中に、'checked_items'というキーが、もし存在すれば、その値を使う。
        //  もし存在しなければ（nullのような扱いになるので）、代わりに、空の配列[]を使う」
        // この ?? があることで、何もチェックされなかった場合の、エラーを予防できる
        $checkedItems = $allData['checked_items'] ?? [];
        $memo = $allData['memo'] ?? '';

        // レシピIDをキーにして、その中に「レシピ名」と「材料リスト」を持たせる、2階層の配列を、これから組み立てる
        $displayItems = [];

        foreach ($checkedItems as $item) {
            // $item が、例えば 'ingredient_70' だったとする。
            // explode('_', ...) は、「_ という記号の場所で、文字列を切り分けて、配列として返す」関数
            $parts = explode('_', $item);

            // $parts = ['ingredient', '70'] のような配列になる
            $type = $parts[0]; // 0番目：種類（'ingredient' か 'condiment'）
            $id = $parts[1];   // 1番目：ID（数字）

            if ($type === 'ingredient') {
                $ri = $recipeIngredientRepository->find($id);
                if ($ri) {
                    $recipeId = $ri->getRecipe()->getId();
                    $recipeName = $ri->getRecipe()->getName();

                    // このレシピIDが、まだ $displayItems に登場していなければ、先に「引き出し」を作っておく
                    // （毎回作り直すと、前に入れた材料が消えてしまうため、isset() で確認してから作る）
                    if (!isset($displayItems[$recipeId])) {
                        $displayItems[$recipeId] = [
                            'recipeName' => $recipeName,
                            'items' => [],
                        ];
                    }

                    // 「からあげ」の引き出しの中の、材料リストに、今処理している1件を追加する
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

        return $this->render('shopping_list/confirm.html.twig', [
            'displayItems' => $displayItems,
            'memo' => $memo,
        ]);
    }

    #[Route('/shopping-list/new', name: 'app_shopping_list_new', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function new(SessionInterface $session, RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response

    {    // セッションから、さっき保存しておいたレシピIDの配列を取り出す
        // 万が一セッションに何も無ければ（直接このURLにアクセスした場合など）、空配列をデフォルトにする

        $recipeIds = $session->get('shopping_list_recipe_ids', []);
        // Recipeだけでなく、Favorite（人数の情報を持っている）を、まとめて取得する これから、複雑な検索条件を、自分で組み立てます」という宣言.

        $favorites = $favoriteRepository->createQueryBuilder('f')
            ->where('f.user = :user')
            // 「ユーザーが、指定した人と一致する」という条件
            ->andWhere('f.recipe IN (:recipeIds)')
            ->setParameter('user', $this->getUser())
            ->setParameter('recipeIds', $recipeIds)
            // ->setParameter(...)：:userや:recipeIdsという、条件の中の"空欄"に、実際の値をはめ込む
            ->getQuery()
            ->getResult();
        // 「レシピのIDだけを追いかけるのではなく、"お気に入り"という、ユーザー・レシピ・人数がセットになった記録そのものを取ってくることで、人数の情報も自然に手に入る」

        return $this->render('shopping_list/new.html.twig', [
            'favorites' => $favorites,
        ]);
    }



    #[Route('/shopping-list/send', name: 'app_shopping_list_send', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function send(
        Request $request,
        SessionInterface $session,
        MailerInterface $mailer
    ): Response {
        $emailAddress = $request->request->get('email_address');

        $email = (new Email())
            ->from('meikotoulouse0726@gmail.com')
            ->to($emailAddress)
            ->subject('Votre liste de courses - Wasyoku Sensei')
            ->text('ここに、買い物リストの中身を書く');

        $mailer->send($email);

        $this->addFlash('shopping_list_sent', true);

        return $this->redirectToRoute('app_shopping_list_confirm_show');
    }
}
