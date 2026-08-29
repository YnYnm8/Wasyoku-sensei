<?php

namespace App\Controller;

use App\Repository\AsianShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/asian-shop')]
final class AsianShopController extends AbstractController
{
    /**
     * Lists Asian grocery shops, optionally filtered by postcode prefix.
     */
    #[Route(name: 'app_asian_shop_index', methods: ['GET'])]
    public function index(Request $request, AsianShopRepository $asianShopRepository): Response
    {
        // URLから郵便番号を受け取る（例：?postcode=310）。無ければnull
        $postcode = $request->query->get('postcode');

        if ($postcode) {
            $shops = $asianShopRepository->findByPostcodePrefix($postcode);
        } else {
            // 検索条件が無ければ、全件表示
            $shops = $asianShopRepository->findAll();
        }

        return $this->render('asian_shop/index.html.twig', [
            'shops' => $shops,
            'postcode' => $postcode,
        ]);
    }
}