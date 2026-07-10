<?php

namespace App\DataFixtures;

use App\Entity\CondimentSubstituteGroup;
use App\Entity\CondimentSubstitute;
use App\Entity\Condiment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CondimentSubstituteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $mirin = $this->getReference('condiment-mirin', Condiment::class);

        $mirinGroup1 = new CondimentSubstituteGroup();
        $mirinGroup1->setLabel('Vin blanc + sucre');
        $mirinGroup1->setCondiment($mirin);
        $manager->persist($mirinGroup1);

        $substitute1 = new CondimentSubstitute();
        $substitute1->setName('Vin blanc');
        $substitute1->setQuantity(20);
        $substitute1->setUnit('ml');
        $substitute1->setCondimentSubstituteGroup($mirinGroup1);
        $manager->persist($substitute1);

        $substitute2 = new CondimentSubstitute();
        $substitute2->setName('Sucre');
        $substitute2->setQuantity(5);
        $substitute2->setUnit('g');
        $substitute2->setCondimentSubstituteGroup($mirinGroup1);
        $manager->persist($substitute2);

        $mirinGroup2 = new CondimentSubstituteGroup();
        $mirinGroup2->setLabel('Champagne + sel');
        $mirinGroup2->setCondiment($mirin);
        $manager->persist($mirinGroup2);

        $substitute3 = new CondimentSubstitute();
        $substitute3->setName('Champagne');
        $substitute3->setQuantity(20);
        $substitute3->setUnit('ml');
        $substitute3->setCondimentSubstituteGroup($mirinGroup2);
        $manager->persist($substitute3);

        $substitute4 = new CondimentSubstitute();
        $substitute4->setName('Sel');
        $substitute4->setQuantity(1);
        $substitute4->setUnit('pincée');
        $substitute4->setCondimentSubstituteGroup($mirinGroup2);
        $manager->persist($substitute4);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
