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

final class ShoppingListController extends AbstractController
{


    #[Route('/shopping-list/create', name: 'app_shopping_list_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, SessionInterface $session): Response
    { // フォームから送られてきた、recipe_idsという名前のデータを、配列として取り出す
        $recipeIds = $request->request->all('recipe_ids');
        // 「今受け取った、その数字の配列を、セッション（一時的な保管庫）に、覚えさせておく」**という意味です。
        $session->set('shopping_list_recipe_ids', $recipeIds);

        return $this->redirectToRoute('app_shopping_list_new');
    }

    #[Route('/shopping-list/new', name: 'app_shopping_list_new', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function new(SessionInterface $session, RecipeRepository $recipeRepository ,FavoriteRepository $favoriteRepository): Response

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
