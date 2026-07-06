<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        // フィルター処理は RecipeController に引っ越したため、
        // ここでは全レシピを取得するだけにする
        $recipes = $recipeRepository->findAll();

        return $this->render('home/index.html.twig', [
            // ④ Twigに渡して表示する
            'recipes' => $recipes,
        ]);
    }
}