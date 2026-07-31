<?php

namespace App\Controller;

use App\Entity\Condiment;
use App\Entity\CondimentSubstitute;
use App\Entity\CondimentSubstituteGroup;
use App\Form\CondimentType;
use App\Repository\CondimentRepository;
use App\Repository\CondimentSubstituteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\CondimentSubstituteGroupRepository;

#[Route('/condiment')]
final class CondimentController extends AbstractController
{
    #[Route(name: 'app_condiment_index', methods: ['GET'])]
    public function index(CondimentRepository $condimentRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $condiments = $condimentRepository->findAll();
        $totalCount = count($condiments);
        $condiments = array_slice($condiments, ($page - 1) * $limit, $limit);

        $totalPages = (int) ceil($totalCount / $limit);

        return $this->render('condiment/index.html.twig', [
            'condiments' => $condiments,
            'currentPage' => $page,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/new', name: 'app_condiment_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $condiment = new Condiment();
        $form = $this->createForm(CondimentType::class, $condiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($condiment);
            $entityManager->flush();

            return $this->redirectToRoute('app_condiment_edit', ['id' => $condiment->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('condiment/new.html.twig', [
            'condiment' => $condiment,
            'form' => $form,
        ]);
    }
    
    // US3.x：管理者専用の調味料一覧（訪問者向けのindex()とは別に用意）
    #[Route('/admin', name: 'app_condiment_admin_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(CondimentRepository $condimentRepository): Response
    {
        $condiments = $condimentRepository->findAll();

        return $this->render('condiment/admin_index.html.twig', [
            'condiments' => $condiments,
        ]);
    }

    #[Route('/{id}', name: 'app_condiment_show', methods: ['GET'])]
    public function show(Condiment $condiment): Response
    {
        return $this->render('condiment/show.html.twig', [
            'condiment' => $condiment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_condiment_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Condiment $condiment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CondimentType::class, $condiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_condiment_edit', ['id' => $condiment->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('condiment/edit.html.twig', [
            'condiment' => $condiment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condiment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Condiment $condiment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $condiment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($condiment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_condiment_index', [], Response::HTTP_SEE_OTHER);
    }

    // US3.3 CA7・CA8・CA9：ROLE_ADMINのみアクセス可能
    // レシピ編集ページの<dialog>フォームから送信される、代替品の追加処理
    // 材料（Ingredient）と違い、代替品は「グループ」→「個々の代替品」という2階層の構造を持つため、

    #[Route('/{id}/substitute/add', name: 'app_condiment_substitute_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addSubstitute(
        Request $request,
        Condiment $condiment,
        EntityManagerInterface $entityManager,
        CondimentSubstituteGroupRepository $condimentSubstituteGroupRepository
    ): Response {
        // フォームから送信された値を受け取る。trim()で前後の余計な空白を除去
        $name = trim($request->request->get('condimentSubstitute_name', ''));
        $quantity = $request->request->get('quantity');
        $unit = trim($request->request->get('unit', ''));

        // 必須項目が空なら何もせず編集ページへ戻す（簡易バリデーション）
        if ($name === '' || $quantity === null || $unit === '') {
            return $this->redirectToRoute('app_condiment_edit', ['id' => $condiment->getId()]);
        }

        $groupLabel = trim($request->request->get('groupLabel_name', ''));

        // CA8の核心（US1.3のCA8と同じ発想）：
        // 「この調味料」の中で、同じラベルのグループが既にあるか探す
        $condimentSubstituteGroup = $condimentSubstituteGroupRepository->findOneBy([
            'label' => $groupLabel,
            'condiment' => $condiment,
        ]);

        // ① まず「グループ」を新規作成する（例：「白ワイン＋砂糖で代用」というまとまり）
        // このグループが、どのConditment（醤油・みりんなど）の代替品なのかを、setCondiment()で紐付ける
        if (!$condimentSubstituteGroup) {

            $condimentSubstituteGroup = new CondimentSubstituteGroup();
            $condimentSubstituteGroup->setLabel($groupLabel);
            $condimentSubstituteGroup->setCondiment($condiment);
            $entityManager->persist($condimentSubstituteGroup);
        }

        // ② 次に「個々の代替品」を作成する（例：Vin blanc, 20ml）
        // setCondimentSubstituteGroup()で、①で作ったグループに紐付ける（ここを忘れるとDBの必須カラムが空になりエラーになる）
        $condimentSubstitute = new CondimentSubstitute();
        $condimentSubstitute->setName($name);
        // フォームから受け取った値は文字列なので、(float)でDoctrineが期待する数値型に変換する
        $condimentSubstitute->setQuantity((float) $quantity);
        $condimentSubstitute->setUnit($unit);
        $condimentSubstitute->setCondimentSubstituteGroup($condimentSubstituteGroup);
        $entityManager->persist($condimentSubstitute);

        // ①②で persist() した2つのオブジェクトが、ここで初めて同時にDBへ書き込まれる
        $entityManager->flush();

        return $this->redirectToRoute('app_condiment_edit', ['id' => $condiment->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/substitute/{substituteId}/delete', name: 'app_condiment_substitute_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteSubstitute(
        Request $request,
        Condiment $condiment,
        int $substituteId,
        EntityManagerInterface $entityManager,
        CondimentSubstituteRepository $condimentSubstituteRepository
    ): Response {
        $substitute = $condimentSubstituteRepository->find($substituteId);

        if ($substitute && $this->isCsrfTokenValid(
            'delete_substitute' . $substitute->getId(),
            $request->getPayload()->getString('_token')
        )) {
            // 削除する前に、このグループを覚えておく
            $group = $substitute->getCondimentSubstituteGroup();

            $entityManager->remove($substitute);
            $entityManager->flush();

            // 削除した結果、グループの中の代替品が0件になったら、空のグループも一緒に消す
            if ($group->getCondimentSubstitutes()->count() === 0) {
                $entityManager->remove($group);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_condiment_edit', ['id' => $condiment->getId()], Response::HTTP_SEE_OTHER);
    }
}
