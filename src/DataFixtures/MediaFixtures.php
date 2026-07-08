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
        $mediaKaraage->setUrl('https://placehold.co/600x400/png?text=Karaage');
        $mediaKaraage->setRecipe($karaage);
        $manager->persist($mediaKaraage);

        $tamagoyaki = $this->getReference('recipe-tamagoyaki', Recipe::class);
        $mediaTamagoyaki = new Media();
        $mediaTamagoyaki->setName('Tamagoyaki photo');
        $mediaTamagoyaki->setUrl('https://placehold.co/600x400/png?text=Tamagoyaki');
        $mediaTamagoyaki->setRecipe($tamagoyaki);
        $manager->persist($mediaTamagoyaki);

        $oyakodon = $this->getReference('recipe-oyakodon', Recipe::class);
        $mediaOyakodon = new Media();
        $mediaOyakodon->setName('Oyakodon photo');
        $mediaOyakodon->setUrl('https://placehold.co/600x400/png?text=Oyakodon');
        $mediaOyakodon->setRecipe($oyakodon);
        $manager->persist($mediaOyakodon);

        $haricotsGomaae = $this->getReference('recipe-haricots-gomaae', Recipe::class);
        $mediaHaricotsGomaae = new Media();
        $mediaHaricotsGomaae->setName('Haricots Gomaae photo');
        $mediaHaricotsGomaae->setUrl('https://placehold.co/600x400/png?text=Haricots+Gomaae');
        $mediaHaricotsGomaae->setRecipe($haricotsGomaae);
        $manager->persist($mediaHaricotsGomaae);

        $hiyayakko = $this->getReference('recipe-hiyayakko', Recipe::class);
        $mediaHiyayakko = new Media();
        $mediaHiyayakko->setName('Hiyayakko photo');
        $mediaHiyayakko->setUrl('https://placehold.co/600x400/png?text=Hiyayakko');
        $mediaHiyayakko->setRecipe($hiyayakko);
        $manager->persist($mediaHiyayakko);

        $mapoTofu = $this->getReference('recipe-mapo-tofu', Recipe::class);
        $mediaMapoTofu = new Media();
        $mediaMapoTofu->setName('Mapo tofu photo');
        $mediaMapoTofu->setUrl('https://placehold.co/600x400/png?text=Mapo+Tofu');
        $mediaMapoTofu->setRecipe($mapoTofu);
        $manager->persist($mediaMapoTofu);

        $donburiSaumon = $this->getReference('recipe-donburi-saumon', Recipe::class);
        $mediaDonburiSaumon = new Media();
        $mediaDonburiSaumon->setName('Donburi saumon photo');
        $mediaDonburiSaumon->setUrl('https://placehold.co/600x400/png?text=Donburi+Saumon');
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