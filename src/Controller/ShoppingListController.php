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
        // ''これはメモがなかったらからの配列として送ってくださいといういみ
        $memo = $request->request->get('memo', '');

        // セッションに、今回チェックされた内容・メモを保存する
        $session->set('shopping_confirm_allData', $allData);

        return $this->redirectToRoute('app_shopping_list_confirm_show');
    }


    // ③ セッションから、チェック内容・メモを取り出して、確認画面（Image 2相当）を表示する
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
    // この？？があることで、エラーの予防になる
    $checkedItems = $allData['checked_items'] ?? [];
    $memo = $allData['memo'] ?? '';

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
                $displayItems[] = [
                    'name' => $ri->getIngredient()->getName(),
                    'quantity' => $ri->getQuantity(),
                    'unit' => $ri->getUnit(),
                ];
            }
        } elseif ($type === 'condiment') {
            $rc = $recipeCondimentRepository->find($id);
            if ($rc) {
                $displayItems[] = [
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
}
