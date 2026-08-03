<?php

namespace App\DataFixtures;

use App\Entity\AsianShop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AsianShopFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $shops = [
            [
                'name' => 'Machi Ya',
                'postcode' => '31000',
                'address' => '10 Place du Parlement, Toulouse',
                'telephone' => '05 61 00 00 01',
                'explanationService' => 'Épicerie fine japonaise au cœur des Carmes : matcha, sakés, mochis et produits importés du Japon, avec conseils personnalisés.',
            ],
            [
                'name' => 'Au Petit Japon',
                'postcode' => '31000',
                'address' => '31 rue de la Colombette, Toulouse',
                'telephone' => '05 61 00 00 02',
                'explanationService' => 'Petite boutique de quartier proposant un large choix de produits japonais et asiatiques.',
            ],
            [
                'name' => 'Paris Store Toulouse',
                'postcode' => '31100',
                'address' => '13 rue Paul-Gauguin, Toulouse',
                'telephone' => '05 61 00 00 03',
                'explanationService' => 'Franchise nationale spécialisée dans les produits asiatiques, grand choix de sauces, nouilles et condiments japonais.',
            ],
            [
                'name' => 'Asie Market',
                'postcode' => '31000',
                'address' => '6 rue Émile-Baudot, Toulouse',
                'telephone' => '05 61 00 00 04',
                'explanationService' => 'Épicerie spécialisée dans les produits venus des pays d\'Asie, dont un rayon dédié aux condiments japonais.',
            ],
            [
                'name' => 'King Fat – Centre-ville',
                'postcode' => '31000',
                'address' => '3 rue Denfert Rochereau, Toulouse',
                'telephone' => '05 61 63 67 93',
                'explanationService' => 'Supermarché asiatique historique : condiments, épices, produits frais et ustensiles de cuisine japonaise.',
            ],
            [
                'name' => 'King Fat – Montaudran',
                'postcode' => '31400',
                'address' => '157 route de Labège, Toulouse',
                'telephone' => '05 62 71 38 40',
                'explanationService' => 'Supermarché asiatique de grande surface, large choix de sauces, riz, nouilles et produits surgelés japonais.',
            ],
            [
                'name' => 'Koko Mart',
                'postcode' => '31000',
                'address' => 'Quartier Esquirol, Toulouse',
                'telephone' => '05 61 00 00 07',
                'explanationService' => 'Épicerie asiatique de proximité en plein centre-ville, spécialisée dans les produits japonais et coréens.',
            ],
            [
                'name' => 'Bangla Bazar',
                'postcode' => '31000',
                'address' => 'Toulouse centre',
                'telephone' => '05 61 00 00 08',
                'explanationService' => 'Épicerie asiatique proposant un large choix de produits communs et de condiments à prix raisonnables.',
            ],
            [
                'name' => 'Toulouse Saké Club',
                'postcode' => '31000',
                'address' => 'Toulouse centre',
                'telephone' => '05 61 00 00 09',
                'explanationService' => 'Cave à saké et épicerie fine japonaise : large sélection de sakés, tofus, algues et thé matcha.',
            ],
            [
                'name' => 'Super U Drive Asie',
                'postcode' => '31000',
                'address' => 'Toulouse',
                'telephone' => '05 61 00 00 10',
                'explanationService' => 'Rayon dédié aux produits asiatiques disponible en drive, pratique pour les condiments courants (sauce soja, mirin, saké de cuisine).',
            ],
            [
                'name' => 'Dia Exotic',
                'postcode' => '31500',
                'address' => '66 rue Louis Plana, Toulouse',
                'telephone' => '09 83 07 68 32',
                'explanationService' => 'Épicerie exotique proposant plus de 3000 références (épicerie, frais, surgelés), avec service de livraison sur Toulouse et son agglomération.',
            ],
            [
                'name' => 'Épicerie asiatique - Avenue de Muret',
                'postcode' => '31300',
                'address' => '228 avenue de Muret, Toulouse',
                'telephone' => '05 61 00 00 11',
                'explanationService' => 'Restaurant asiatique avec une petite épicerie attenante proposant des produits d\'importation.',
            ],
            [
                'name' => 'Épicerie asiatique - Rue Jonas',
                'postcode' => '31200',
                'address' => '6 rue Jonas, Toulouse',
                'telephone' => '05 61 00 00 12',
                'explanationService' => 'Épicerie de quartier proposant un assortiment de produits asiatiques courants.',
            ],
        ];

        foreach ($shops as $shopData) {
            $shop = new AsianShop();
            $shop->setName($shopData['name']);
            $shop->setPostcode($shopData['postcode']);
            $shop->setAddress($shopData['address']);
            $shop->setTelephone($shopData['telephone']);
            $shop->setExplanationService($shopData['explanationService']);

            $manager->persist($shop);
        }

        $manager->flush();
    }
}
