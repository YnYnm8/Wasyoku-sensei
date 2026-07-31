<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;
use App\Entity\Favorite;
use Symfony\Component\HttpFoundation\Request;


#[Route('/favorite')]
final class FavoriteController extends AbstractController
{
    #[Route('', name: 'app_favorite_index', methods: ['GET'])]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $favorites = $favoriteRepository->findBy(['user' => $this->getUser()]);

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
            'totalCount' => count($favorites),
        ]);
    }



    #[Route('/{id}/add', name: 'app_favorite_add', methods: ['POST'])]
    public function addFavorite(
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        FavoriteRepository $favoriteRepository,
        Request $request
    ): Response {
        // 既にお気に入り済みでないか確認（二重登録を防ぐ）
        $existing = $favoriteRepository->findOneBy([
            'user' => $this->getUser(),
            'recipe' => $recipe,
        ]);

        if (!$existing && $this->isCsrfTokenValid(
            'add_favorite' . $recipe->getId(),   // ① 期待する答え
            $request->getPayload()->getString('_token')   // ② 実際に送られきた答え
        )) {
            $personCount = $request->request->getInt('person_count', 1);

            $favorite = new Favorite();
            $favorite->setUser($this->getUser());
            $favorite->setRecipe($recipe);
            $favorite->setPerson($personCount);
            $entityManager->persist($favorite);
            $entityManager->flush();

            // メイン写真（stepOrderがnullのもの）を取得
            $mainPhoto = $recipe->getMedia()->filter(fn($m) => $m->getStepOrder() === null)->first();

            // 「お気に入りに追加されたレシピの情報」を、次の1回だけ表示するために保存しておく
            $this->addFlash('favori_added', [
                'id' => $recipe->getid(),
                'name' => $recipe->getName(),
                'personCount' => $personCount,
                'photoUrl' => $mainPhoto ? $mainPhoto->getUrl() : null,
            ]);
        }

        return $this->redirectToRoute('app_recipe_index');
    }


    #[Route('/{id}/remove', name: 'app_favorite_remove', methods: ['POST'])]
    public function removeFavorite(
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        FavoriteRepository $favoriteRepository,
        Request $request
    ): Response {
        // 既にお気に入り済みでないか確認（二重登録を防ぐ）
        $existing = $favoriteRepository->findOneBy([
            'user' => $this->getUser(),
            'recipe' => $recipe,
        ]);

        if ($existing && $this->isCsrfTokenValid(
            'delete_favorite' . $recipe->getId(),   // ① 期待する答え
            $request->getPayload()->getString('_token')         // ② 実際に送られきた答え
        )) {


            $entityManager->remove($existing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index');
    }
}
