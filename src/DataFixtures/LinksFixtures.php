<?php

namespace App\DataFixtures;

use App\Entity\Links;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LinksFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tblParents = ['footer', 'autre'];

        for ($i = 1; $i < 21; $i++) {
            $link = new Links();
            $link->setTitre("Link n° " . $i)
                ->setParent($tblParents[array_rand($tblParents)])
                ->setPath('#')
                ->setGroupe($this->getReference("Groupe links n° " . rand(1,8)));
            $manager->persist($link);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [GroupesLinksFixtures::class];
    }
}
