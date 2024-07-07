<?php

namespace App\DataFixtures;

use App\Entity\ImagesCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImagesCategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<11;$i++)
        {
            $categorieImage=new ImagesCategories();
            $categorieImage->setNom("Catégorie image n° ".$i);
            $manager->persist($categorieImage);
        }       
        $manager->persist($categorieImage);

        $manager->flush();
    }
}
