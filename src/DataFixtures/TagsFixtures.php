<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagsFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        $tblIcones = ['fa-solid fa-download','fa-solid fa-trash-can','fa-regular fa-file-lines','fa-regular fa-folder-open', 'fa-regular fa-pen-to-square', 'fa-regular fa-user', 'fa-solid fa-location-dot', 'fa-brands fa-twitter'];
        $tblCouleurs = ['blue', 'darkblue', 'green', 'darkgreen', 'red', 'orange', 'darkgray', 'lightgray'];
        $tblParents = ['Galeries', 'Images'];
        for ($i = 1; $i < 101; $i++) {
            $tag = new Tags();
            $tag->setTitre("Tag n° " . $i)
                ->setIcone($tblIcones[array_rand($tblIcones)])
                ->setParent($tblParents[array_rand($tblParents)])
                ->setCouleur($tblCouleurs[array_rand($tblCouleurs)]);

            $manager->persist($tag);
        }
        $manager->flush();
    }
}
