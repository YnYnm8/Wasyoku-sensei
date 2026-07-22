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

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}