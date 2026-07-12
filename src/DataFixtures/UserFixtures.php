<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'user-admin';
    public const USER_AKIKO_REFERENCE = 'user-akiko';
    public const USER_JOHN_REFERENCE = 'user-john';
    public const USER_LUZ_REFERENCE = 'user-luz';

    public function __construct(
        
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // --- 管理者ユーザー ---
        $admin = new User();
        $admin->setEmail('admin@washoku-sensei.fr');
        $admin->setUsername('admin');
        $admin->setRole(RoleEnum::ADMIN);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'AdminPass123!')
        );
        $manager->persist($admin);
        $this->addReference(self::ADMIN_REFERENCE, $admin);

        // --- 一般ユーザー（cahierのペルソナに合わせる）---
        $akiko = new User();
        $akiko->setEmail('akiko@example.com');
        $akiko->setUsername('shiraishi_akiko');
        $akiko->setRole(RoleEnum::USER);
        $akiko->setPassword(
            $this->passwordHasher->hashPassword($akiko, 'UserPass123!')
        );
        $manager->persist($akiko);
        $this->addReference(self::USER_AKIKO_REFERENCE, $akiko);

        $john = new User();
        $john->setEmail('john@example.com');
        $john->setUsername('john');
        $john->setRole(RoleEnum::USER);
        $john->setPassword(
            $this->passwordHasher->hashPassword($john, 'UserPass123!')
        );
        $manager->persist($john);
        $this->addReference(self::USER_JOHN_REFERENCE, $john);

        $luz = new User();
        $luz->setEmail('luz@example.com');
        $luz->setUsername('luz');
        $luz->setRole(RoleEnum::USER);
        $luz->setPassword(
            $this->passwordHasher->hashPassword($luz, 'UserPass123!')
        );
        $manager->persist($luz);
        $this->addReference(self::USER_LUZ_REFERENCE, $luz);

        $manager->flush();
    }
}