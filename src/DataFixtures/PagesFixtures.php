<?php

namespace App\DataFixtures;

use App\Entity\Pages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PagesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $page = new Pages();
            $page->setTitre("Titre de la page n° " . $i + 1)
                ->setSousTitre("Sous-titre de la page n° " . $i + 1)
                ->setEtat("publiee")
                ->setOrdre($i + 1)
                ->setSlug($page->getTitre());
            $this->setReference("Page numéro " . $i, $page);
            $manager->persist($page);
        }
        $manager->flush();
    }
}
