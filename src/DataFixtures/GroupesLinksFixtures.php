<?php

namespace App\DataFixtures;

use App\Entity\GroupesLinks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupesLinksFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 9; $i++) {
            $groupe = new GroupesLinks();
            $groupe->setTitre("Groupe de links n° " . $i);
            $groupe->setAfficherTitre(true);
            $groupe->setParent('footer');
            $manager->persist($groupe);

            $this->setReference("Groupe links n° " . $i, $groupe);
        }
        $manager->flush();
    }
}
