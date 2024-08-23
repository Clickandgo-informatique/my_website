<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new Users();

        $user->setNickname('Emmanuel')
            ->setEmail('mail@clickandgo-informatique.com')
            ->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($user, 'SuperAdmin2024!'))
            ->isVerified(true);

        $manager->persist($user);

        $user = new Users();

        $user->setNickname('Micaella')
            ->setEmail('l_micaella@hotmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($user, 'Admin2024!'))
            ->isVerified(true);

        $manager->persist($user);

        for ($i = 1; $i < 11; $i++) {

            $user = new Users();
            $user->setNickname('Utilisateur n° ' . $i)
                ->setEmail('utilisateur00' . $i . '@hotmail.com')
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'User2024!'))
                ->isVerified(false);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
