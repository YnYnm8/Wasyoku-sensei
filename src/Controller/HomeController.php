<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
  public function index(RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response
{
    // 材料1：人気のレシピ、3件
    $recipes = $recipeRepository->findMostPopular(3);

    // 材料2：あなたの、お気に入りIDの、一覧（前に、RecipeControllerでも、作った、同じもの）
    $favoriteRecipeIds = [];
    if ($this->getUser()) {
        $favorites = $favoriteRepository->findBy(['user' => $this->getUser()]);
        foreach ($favorites as $favorite) {
            $favoriteRecipeIds[] = $favorite->getRecipe()->getId();
        }
    }

    // 2つの材料を、Twigに、渡す
    return $this->render('home/index.html.twig', [
        'recipes' => $recipes,
        'favoriteRecipeIds' => $favoriteRecipeIds,
    ]);
}
}