<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $karaage = $this->getReference('recipe-karaage', Recipe::class);
        $mediaKaraage = new Media();
        $mediaKaraage->setName('Karaage photo');
        $mediaKaraage->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgHjusf89Tj83K7Xik5sxjwSzYpC71k6OkM-1UMlwGJg&s=10');
        $mediaKaraage->setRecipe($karaage);
        $manager->persist($mediaKaraage);

        $tamagoyaki = $this->getReference('recipe-tamagoyaki', Recipe::class);
        $mediaTamagoyaki = new Media();
        $mediaTamagoyaki->setName('Tamagoyaki photo');
        $mediaTamagoyaki->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9SohbpNrnPL8v9uOnkFSenxk9D7hUghg2-bpHHuVZzA&s=10');
        $mediaTamagoyaki->setRecipe($tamagoyaki);
        $manager->persist($mediaTamagoyaki);

        $oyakodon = $this->getReference('recipe-oyakodon', Recipe::class);
        $mediaOyakodon = new Media();
        $mediaOyakodon->setName('Oyakodon photo');
        $mediaOyakodon->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqxTXviEXS3NgWJjxNyIHycMEL2GwYSDVUk6jKCiEAHg&s=10');
        $mediaOyakodon->setRecipe($oyakodon);
        $manager->persist($mediaOyakodon);

        $haricotsGomaae = $this->getReference('recipe-haricots-gomaae', Recipe::class);
        $mediaHaricotsGomaae = new Media();
        $mediaHaricotsGomaae->setName('Haricots Gomaae photo');
        $mediaHaricotsGomaae->setUrl('https://www.sirogohan.com/_files/recipe/images/gomaaeingen/ingengomaaesai6449.JPG');
        $mediaHaricotsGomaae->setRecipe($haricotsGomaae);
        $manager->persist($mediaHaricotsGomaae);

        $hiyayakko = $this->getReference('recipe-hiyayakko', Recipe::class);
        $mediaHiyayakko = new Media();
        $mediaHiyayakko->setName('Hiyayakko photo');
        $mediaHiyayakko->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlJHBNa1Nx4rQ2uHf5c-3ykmWHwHs-c_-ZWOsCn0HcoQ&s=10');
        $mediaHiyayakko->setRecipe($hiyayakko);
        $manager->persist($mediaHiyayakko);

        $mapoTofu = $this->getReference('recipe-mapo-tofu', Recipe::class);
        $mediaMapoTofu = new Media();
        $mediaMapoTofu->setName('Mapo tofu photo');
        $mediaMapoTofu->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbrQYhaMPGP01H98wMuQBUnglb9jVkL9TX21FyGd4SGQ&s=10');
        $mediaMapoTofu->setRecipe($mapoTofu);
        $manager->persist($mediaMapoTofu);

        $donburiSaumon = $this->getReference('recipe-donburi-saumon', Recipe::class);
        $mediaDonburiSaumon = new Media();
        $mediaDonburiSaumon->setName('Donburi saumon photo');
        $mediaDonburiSaumon->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-h79NOkCDEthVX4xcx-yWWa5Uv1te2Sp3zFisrix37A&s=10');
        $mediaDonburiSaumon->setRecipe($donburiSaumon);
        $manager->persist($mediaDonburiSaumon);
      
        // ==========================================
        // ステップ写真（からあげ）
        // ==========================================
        $karaageSteps = [
            ['file' => 'karaage_step1.png', 'order' => 1, 'description' => 'Mariner le poulet avec la sauce soja et le saké'],
            ['file' => 'karaage_step2.png', 'order' => 2, 'description' => 'Ajouter l\'ail et le gingembre râpés'],
            ['file' => 'karaage_step3.png', 'order' => 3, 'description' => 'Enrober les morceaux de fécule de pomme de terre'],
            ['file' => 'karaage_step4.png', 'order' => 4, 'description' => 'Faire frire jusqu\'à ce que ce soit doré et croustillant'],
        ];
        foreach ($karaageSteps as $step) {
            $media = new Media();
            $media->setName('Karaage étape ' . $step['order']);
            $media->setUrl('/images/steps/karaage/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($karaage);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（卵焼き）
        // ==========================================
        $tamagoyakiSteps = [
            ['file' => 'tamagoyaki_1.png', 'order' => 1, 'description' => 'Battre les oeufs avec la sauce soja, le sucre et le sel'],
            ['file' => 'tamagoyaki_2.png', 'order' => 2, 'description' => 'Verser une fine couche dans la poêle chaude'],
            ['file' => 'tamagoyaki_3.png', 'order' => 3, 'description' => 'Rouler l\'omelette'],
            ['file' => 'tamagoyaki_4.png', 'order' => 4, 'description' => 'Couper en tranches'],
        ];
        foreach ($tamagoyakiSteps as $step) {
            $media = new Media();
            $media->setName('Tamagoyaki étape ' . $step['order']);
            $media->setUrl('/images/steps/tamagoyaki/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($tamagoyaki);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（親子丼）
        // ==========================================
        $oyakodonSteps = [
            ['file' => 'oyakodon_1.png', 'order' => 1, 'description' => 'Émincer l\'oignon'],
            ['file' => 'oyakodon_2.png', 'order' => 2, 'description' => 'Cuire le poulet'],
            ['file' => 'oyakodon_3.png', 'order' => 3, 'description' => 'Ajouter les oeufs battus'],
            ['file' => 'oyakodon_4.png', 'order' => 4, 'description' => 'Dresser sur le bol de riz'],
        ];
        foreach ($oyakodonSteps as $step) {
            $media = new Media();
            $media->setName('Oyakodon étape ' . $step['order']);
            $media->setUrl('/images/steps/oyakodon/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($oyakodon);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（いんげんの胡麻和え）：3枚のみ
        // ==========================================
        $haricotsSteps = [
            ['file' => 'ingen_step1.png', 'order' => 1, 'description' => 'Cuire les haricots verts à l\'eau bouillante'],
            ['file' => 'ingen_step2.png', 'order' => 2, 'description' => 'Égoutter et laisser refroidir'],
            ['file' => 'ingen_step3.png', 'order' => 3, 'description' => 'Mélanger avec la sauce soja, le sucre et le sésame'],
        ];
        foreach ($haricotsSteps as $step) {
            $media = new Media();
            $media->setName('Haricots Gomaae étape ' . $step['order']);
            $media->setUrl('/images/steps/haricots/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($haricotsGomaae);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（冷奴）
        // ==========================================
        $hiyayakkoSteps = [
            ['file' => 'hiyayakko_step1.png', 'order' => 1, 'description' => 'Sortir le tofu et l\'égoutter'],
            ['file' => 'hiyayakko_step2.png', 'order' => 2, 'description' => 'Disposer dans un bol'],
            ['file' => 'hiyayakko_step3.png', 'order' => 3, 'description' => 'Garnir de ciboule et de gingembre'],
            ['file' => 'hiyayakko_step4.png', 'order' => 4, 'description' => 'Arroser de sauce soja'],
        ];
        foreach ($hiyayakkoSteps as $step) {
            $media = new Media();
            $media->setName('Hiyayakko étape ' . $step['order']);
            $media->setUrl('/images/steps/hiyayakko/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($hiyayakko);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（麻婆豆腐）
        // ==========================================
        $mapoTofuSteps = [
            ['file' => 'mapotofu_step1.png', 'order' => 1, 'description' => 'Faire revenir le porc haché avec l\'ail et le gingembre'],
            ['file' => 'mapotofu_step2.png', 'order' => 2, 'description' => 'Ajouter le miso, la sauce soja et le saké'],
            ['file' => 'mapotofu_step3.png', 'order' => 3, 'description' => 'Ajouter le tofu coupé en cubes'],
            ['file' => 'mapotofu_step4.png', 'order' => 4, 'description' => 'Laisser mijoter et garnir de poireau'],
        ];
        foreach ($mapoTofuSteps as $step) {
            $media = new Media();
            $media->setName('Mapo Tofu étape ' . $step['order']);
            $media->setUrl('/images/steps/mapotofu/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($mapoTofu);
            $manager->persist($media);
        }

        // ==========================================
        // ステップ写真（漬け丼）
        // ==========================================
        $donburiSaumonSteps = [
            ['file' => 'donburisaumon_step1.png', 'order' => 1, 'description' => 'Couper le saumon en cubes'],
            ['file' => 'donburisaumon_step2.png', 'order' => 2, 'description' => 'Préparer la marinade de sauce soja et mirin'],
            ['file' => 'donburisaumon_step3.png', 'order' => 3, 'description' => 'Laisser mariner le saumon'],
            ['file' => 'donburisaumon_step4.png', 'order' => 4, 'description' => 'Dresser sur le riz avec ciboule, sésame et wasabi'],
        ];
        foreach ($donburiSaumonSteps as $step) {
            $media = new Media();
            $media->setName('Donburi Saumon étape ' . $step['order']);
            $media->setUrl('/images/steps/donburisaumon/' . $step['file']);
            $media->setType('photo');
            $media->setStepOrder($step['order']);
            $media->setStepDescription($step['description']);
            $media->setRecipe($donburiSaumon);
            $manager->persist($media);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
