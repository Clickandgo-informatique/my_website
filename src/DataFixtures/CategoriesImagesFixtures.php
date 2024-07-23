<?php

namespace App\DataFixtures;

use App\Entity\CategoriesImages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesImagesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<11;$i++)
        {
            $categorieImage=new CategoriesImages();
            $categorieImage->setNom("Catégorie image n° ".$i);
            $manager->persist($categorieImage);
        }       
        $manager->persist($categorieImage);

        $manager->flush();
    }
}
