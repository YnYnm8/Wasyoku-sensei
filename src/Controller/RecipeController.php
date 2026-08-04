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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeCondimentRepository;
use App\Entity\RecipeCondiment;
use App\Repository\CondimentRepository;
use App\Repository\FavoriteRepository;
use App\Entity\Media;
use App\Repository\MediaRepository;


#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    /**
     * Display the paginated, public recipe list.
     * Supports filtering by difficulty, main ingredient category, season,
     * or a free-text list of ingredient names (one criterion at a time).
     */
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, Request $request, FavoriteRepository $favoriteRepository): Response
    {
        $favoriteRecipeIds = [];
        if ($this->getUser()) {
            $favorites = $favoriteRepository->findBy(['user' => $this->getUser()]);
            // $favorites の中身を、1個ずつ順番に取り出しながら処理する
            foreach ($favorites as $favorite) {
                $favoriteRecipeIds[] = $favorite->getRecipe()->getId();
            }
        }
        // 現在の「ページ番号」をURLから受け取る（例: ?page=2）
        // 指定が無ければ、1ページ目とする
        $page = $request->query->getInt('page', 1);
        $limit = 3;


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
            $recipes = $recipeRepository->findBy(['level' => $levelEnum], null, $limit, ($page - 1) * $limit);

            // 「1つの条件」で完結する、シンプルな検索　 （DBに直接、件数を聞くことができる）
            $totalCount = $recipeRepository->count(['level' => $levelEnum]);
        } elseif ($main) {
            $mainEnum = RecipeMainCategory::from($main);
            $recipes = $recipeRepository->findBy(['mainCategory' => $mainEnum], null, $limit, ($page - 1) * $limit);
            $totalCount = $recipeRepository->count(['mainCategory' => $mainEnum]);
        } elseif ($season) {
            $seasonEnum = RecipeSeason::from($season);
            // ($page-1) * $limitこれは一ページのときには0件目からという計算
            $recipes = $recipeRepository->findBy(['season' => $seasonEnum], null, $limit, ($page - 1) * $limit);
            $totalCount = $recipeRepository->count(['season' => $seasonEnum]);
        } elseif ($ingredients) {
            // explode(区切り文字, 分解したい文字列)
            // 1つのまとまった文字列を、指定した記号の場所で粉々に分解して、
            // 配列（複数の部品）にする関数です
            $ingredientArray = explode(' ', $ingredients);
            // 分解してできた配列を、Repositoryの検索専用関数に渡す
            $allrecipes = $recipeRepository->findByIngredientNames($ingredientArray);
            // 「複数のテーブルをまたぐ」、複雑な検索（DBに、直接「複雑な条件の件数」を聞く機能が無いので、一度取得してから、手元で数えるしかない）
            $totalCount = count($allrecipes);
            $recipes = array_slice($allrecipes, ($page - 1) * $limit, $limit);
        } else {
            $allrecipes = $recipeRepository->findAll();
            $totalCount = count($allrecipes);
            $recipes = array_slice($allrecipes, ($page - 1) * $limit, $limit);
        }

        // ページの全体数をそのページに載せる数でわり、それを整数（INT）にしてね。それが全体の数だよということになります・ceil( 2.333... )→ 3.0（切り上げられた、でもまだfloat型）
        $totalPages = (int)ceil($totalCount / $limit);
        return $this->render('recipe/index.html.twig', [
            // ④ Twigに渡して表示する
            'recipes' => $recipes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'favoriteRecipeIds' => $favoriteRecipeIds,
        ]);
    }

    // US1.3 CA1・CA3・CA4：ROLE_ADMINのみアクセス可能
    // 新しいレシピを、基本情報（name, description, season, time, level, mainCategory）だけで作成するアクション
    // 材料・調味料はここでは扱わない。作成後に編集ページへ移動してから追加する
    /**
     * Create a new recipe from its basic fields only (name, description,
     * season, time, level, main category). Ingredients and condiments are
     * added afterwards, from the edit page. Admin only.
     */
    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // 中身が空のRecipeオブジェクトを、まずメモリ上に作る（この時点ではまだDBに保存されていない）
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // persist()は「これから保存するよ」とDoctrineに伝えるだけで、まだDBには書き込まれない
            $entityManager->persist($recipe);
            // flush()を呼んだ瞬間に、実際にINSERT文が発行され、$recipeにIDが自動で割り振られる
            $entityManager->flush();

            // CA4：作成後は一覧ではなく、そのレシピの編集ページへ自動遷移する
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }
    // US1.3：管理者専用のレシピ一覧（訪問者向けのindex()とは別に用意）
    // フィルターやお気に入り機能は不要。全件を編集・削除しやすい形で並べるだけ
    /**
     * Display the full recipe list for the admin back-office.
     * No filters and no favorites data: every recipe is listed for
     * easy editing/deletion. Admin only.
     */
    #[Route('/admin', name: 'app_recipe_admin_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('recipe/admin_index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    // 訪問者・管理者どちらもアクセス可能（保護なし）
    /**
     * Display a single recipe's detail page.
     * Public route, accessible to both visitors and logged-in users.
     */
    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe, FavoriteRepository $favoriteRepository): Response
    {
        $favoriteRecipeIds = [];
        if ($this->getUser()) {
            $favorites = $favoriteRepository->findBy(['user' => $this->getUser()]);
            foreach ($favorites as $favorite) {
                $favoriteRecipeIds[] = $favorite->getRecipe()->getId();
            }
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'favoriteRecipeIds' => $favoriteRecipeIds,
        ]);
    }

    // US1.3（暗黙のDelete要件）：ROLE_ADMINのみアクセス可能
    // CSRFトークンを検証してから削除することで、外部サイトから勝手に削除リクエストを送られるのを防ぐ
    /**
     * Delete a recipe after validating its CSRF token.
     * Admin only.
     */
    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        // レシピ自体が消えるので、編集ページには戻さず一覧ページへ
        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    // US1.3 CA5・CA6：ROLE_ADMINのみアクセス可能
    // 基本情報の編集フォームに加えて、材料一覧（ingredients）もTwigに渡し、
    // 同じページ内の<dialog>で使う「既存材料の候補リスト」として使う
    /**
     * Edit a recipe's basic fields. The same page also lists existing
     * ingredients/condiments (used by the "add" dialogs on the template).
     * Admin only.
     */
    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        IngredientRepository $ingredientRepository,
        CondimentRepository $condimentRepository
    ): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 既にDB上に存在するRecipeを更新するだけなので、persist()は不要でflush()だけでよい
            $entityManager->flush();
            // CA6：保存後は一覧に飛ばさず、同じ編集ページに留まる（材料・調味料を続けて追加できるように）
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
            // <datalist>で使う、既存材料の名前候補
            'ingredients' => $ingredientRepository->findAll(),
            'condiments' => $condimentRepository->findAll(),
        ]);
    }

    // US1.3 CA7・CA8・CA9：ROLE_ADMINのみアクセス可能
    // レシピ編集ページの<dialog>フォームから送信される、材料の追加処理
    // PRG（Post/Redirect/Get）パターン：処理後は同じ編集ページへリダイレクトし、
    // ページの再読み込み時に最新の材料一覧が反映される（＝CA9の「その場で更新」を実現する方法）
    /**
     * Add an ingredient line to a recipe from the edit page's dialog form.
     * Reuses an existing Ingredient by name if one already exists, to avoid
     * duplicates. Follows the Post/Redirect/Get pattern. Admin only.
     */
    #[Route('/{id}/ingredient/add', name: 'app_recipe_ingredient_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addIngredient(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        IngredientRepository $ingredientRepository
    ): Response {
        // フォームから送信された値を受け取る。trim()で前後の余計な空白を除去
        $name = trim($request->request->get('ingredient_name', ''));
        $quantity = $request->request->get('quantity');
        $unit = trim($request->request->get('unit', ''));

        // 必須項目が空なら何もせず編集ページへ戻す（簡易バリデーション）
        if ($name === '' || $quantity === null || $unit === '') {
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()]);
        }

        // CA8の核心：入力された名前の材料が既にDBにあるか探す
        $ingredient = $ingredientRepository->findOneBy(['name' => $name]);

        if (!$ingredient) {
            // 見つからなければ、新しいIngredientをその場で作成する
            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $entityManager->persist($ingredient);
        }

        // レシピと材料を、分量・単位付きで結びつける中間テーブルのレコードを作成
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($recipe);
        $recipeIngredient->setIngredient($ingredient);
        $recipeIngredient->setQuantity((float) $quantity);
        $recipeIngredient->setUnit($unit);

        $entityManager->persist($recipeIngredient);
        // ここで初めて、新規Ingredient（あれば）とRecipeIngredientの両方がDBに書き込まれる
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }

    // US1.3 CA10：ROLE_ADMINのみアクセス可能
    // レシピから特定の材料（RecipeIngredient）を1件だけ取り除く処理
    // Ingredientマスター自体は削除しない（他のレシピでも使われている可能性があるため、消すのは中間テーブルの行だけ）
    /**
     * Remove one ingredient line from a recipe (join-table row only).
     * The master Ingredient entity is kept, since it may be used by other
     * recipes. Admin only.
     */
    #[Route('/{id}/ingredient/{recipeIngredientId}/delete', name: 'app_recipe_ingredient_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteIngredient(
        Request $request,
        Recipe $recipe,
        int $recipeIngredientId,
        EntityManagerInterface $entityManager,
        RecipeIngredientRepository $recipeIngredientRepository
    ): Response {
        $recipeIngredient = $recipeIngredientRepository->find($recipeIngredientId);

        // CSRFトークンが正しい場合のみ削除を実行（確認ダイアログ＋不正リクエスト対策）
        if ($recipeIngredient && $this->isCsrfTokenValid(
            'delete_ingredient' . $recipeIngredient->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($recipeIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }
    // US1.3 CA11・CA12・CA13：ROLE_ADMINのみアクセス可能
    // 調味料は既にCondimentとして管理されている前提なので、材料と違い「新規作成」は行わず、
    // 既存のCondimentを選んでRecipeに紐付けるだけのシンプルな処理になる
    /**
     * Attach an existing Condiment to a recipe with a quantity and unit.
     * Unlike ingredients, condiments are never created here: they must
     * already exist. Admin only.
     */
    #[Route('/{id}/condiment/add', name: 'app_recipe_condiment_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addCondiment(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        CondimentRepository $condimentRepository
    ): Response {
        $condimentId = $request->request->get('condiment_id');
        $quantity = $request->request->get('quantity');
        $unit = trim($request->request->get('unit', ''));

        if (!$condimentId || $quantity === null || $unit === '') {
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()]);
        }

        // 既存のCondimentをIDで取得する（見つからなければnullが返る）
        $condiment = $condimentRepository->find($condimentId);

        if (!$condiment) {
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()]);
        }

        $recipeCondiment = new RecipeCondiment();
        $recipeCondiment->setRecipe($recipe);
        $recipeCondiment->setCondiment($condiment);
        $recipeCondiment->setQuantity((float) $quantity);
        $recipeCondiment->setUnit($unit);

        $entityManager->persist($recipeCondiment);
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }

    // US1.3 CA14：ROLE_ADMINのみアクセス可能
    /**
     * Remove one condiment line from a recipe, after CSRF validation.
     * Admin only.
     */
    #[Route('/{id}/condiment/{recipeCondimentId}/delete', name: 'app_recipe_condiment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteCondiment(
        Request $request,
        Recipe $recipe,
        int $recipeCondimentId,
        EntityManagerInterface $entityManager,
        RecipeCondimentRepository $recipeCondimentRepository
    ): Response {
        $recipeCondiment = $recipeCondimentRepository->find($recipeCondimentId);

        if ($recipeCondiment && $this->isCsrfTokenValid(
            'delete_condiment' . $recipeCondiment->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($recipeCondiment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }

    // メイン写真の追加（順番なし、レシピにつき通常1枚を想定）
    /**
     * Add the recipe's main photo (stepOrder stays null, which is how it
     * is distinguished from a preparation-step photo). Admin only.
     */
    #[Route('/{id}/media/main/add', name: 'app_recipe_main_photo_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addMainPhoto(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager
    ): Response {
        $url = trim($request->request->get('url', ''));

        if ($url === '') {
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()]);
        }

        $media = new Media();
        $media->setName($recipe->getName() . ' - photo principale');
        $media->setUrl($url);
        $media->setType('photo');
        // stepOrderは設定しない（null のまま）＝ステップ写真と区別する目印
        $media->setRecipe($recipe);

        $entityManager->persist($media);
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }

    // 作り方のステップ写真を追加（順番はサーバー側で自動計算）
    /**
     * Add one preparation-step photo to a recipe.
     * The step order is computed automatically from the count of existing
     * step photos. Admin only.
     */
    #[Route('/{id}/media/step/add', name: 'app_recipe_step_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addStep(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager
    ): Response {
        $url = trim($request->request->get('url', ''));
        $stepDescription = trim($request->request->get('step_description', ''));

        if ($url === '' || $stepDescription === '') {
            return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()]);
        }

        // 既存のステップ写真（stepOrderがnullではないもの）を数えて、次の番号を自動で割り振る
        $existingSteps = array_filter(
            $recipe->getMedia()->toArray(),
            fn(Media $m) => $m->getStepOrder() !== null
        );
        $nextOrder = count($existingSteps) + 1;

        $media = new Media();
        $media->setName('Étape ' . $nextOrder);
        $media->setUrl($url);
        $media->setType('photo');
        $media->setStepOrder($nextOrder);
        $media->setStepDescription($stepDescription);
        $media->setRecipe($recipe);

        $entityManager->persist($media);
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }
    /**
     * Delete a recipe photo (main or step), after CSRF validation.
     * Admin only.
     */
    #[Route('/{id}/media/{mediaId}/delete', name: 'app_recipe_media_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteMedia(
        Request $request,
        Recipe $recipe,
        int $mediaId,
        EntityManagerInterface $entityManager,
        MediaRepository $mediaRepository
    ): Response {
        $media = $mediaRepository->find($mediaId);

        if ($media && $this->isCsrfTokenValid(
            'delete_media' . $media->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($media);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_edit', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }
}