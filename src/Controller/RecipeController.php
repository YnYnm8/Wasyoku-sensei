<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\RecipeLevel;
use App\Enum\RecipeMainCategory;
use App\Enum\RecipeSeason;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
public function index(RecipeRepository $recipeRepository, Request $request): Response
{
    // ① URLから値を受け取る（$request->query->get()）
    $level = $request->query->get('level');
    $main = $request->query->get('mainCategory');
    $season = $request->query->get('season');
    $ingredients = $request->query->get('ingredients');

    if ($level) {
        // ② 文字列をEnumに変換する（RecipeLevel::from()）
        // RecipeLevelクラス自体に対して from() を呼び出し、文字列'easy'を、
        // 対応するEnum（RecipeLevel::EASY）に変換する
        // ::が「実物を作らずに、設計図（クラス）そのものに直接アクセスする記号」
        $levelEnum = RecipeLevel::from($level);
        // ③ Repositoryで検索する（findBy()）
        $recipes = $recipeRepository->findBy(['level' => $levelEnum]);

    } elseif ($main) {
        $mainEnum = RecipeMainCategory::from($main);
        $recipes = $recipeRepository->findBy(['mainCategory' => $mainEnum]);

    } elseif ($season) {
        $seasonEnum = RecipeSeason::from($season);
        $recipes = $recipeRepository->findBy(['season' => $seasonEnum]);

    } elseif ($ingredients) {
        // explode(区切り文字, 分解したい文字列)
        // 1つのまとまった文字列を、指定した記号の場所で粉々に分解して、
        // 配列（複数の部品）にする関数です
        $ingredientArray = explode(' ', $ingredients);
        // 分解してできた配列を、Repositoryの検索専用関数に渡す
        $recipes = $recipeRepository->findByIngredientNames($ingredientArray);

    } else {
        $recipes = $recipeRepository->findAll();
    }

    return $this->render('recipe/index.html.twig', [
        // ④ Twigに渡して表示する
        'recipes' => $recipes,
    ]);
}

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
