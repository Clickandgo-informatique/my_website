<?php

namespace App\DataFixtures;

use App\Entity\Galleries;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GalleriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $typesGaleries = ["Statique horizontale", "Statique verticale", "Diaporama"];
        for ($i = 0; $i < 20; $i++) {
            $galerie = new Galleries();
            $galerie->setTitre("Galerie n° " . $i+1)
                ->setType($faker->randomElement($typesGaleries))
                ->setActive(true)
                ->setPage($this->getReference("Page numéro " . rand(0, 4)));
            $this->addReference("Galerie numéro " . $i+1, $galerie);

            $manager->persist($galerie);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PagesFixtures::class];
    }
}
