<?php

namespace App\DataFixtures;

use App\Entity\SectionsPages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectionsPagesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {

            $section = new SectionsPages();
            $section->setTitre('Section numéro ' . $i);
            $section->setContenu('Contenu de la section n° ' . $i);
            $section->setPage($this->getReference("Page numéro " . rand(0, 5)));
            $manager->persist($section);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PagesFixtures::class];
    }
}
