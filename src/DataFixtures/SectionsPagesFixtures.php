<?php

namespace App\DataFixtures;

use App\Entity\SectionsPages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectionsPagesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {
            for ($j = 1; $j < 4; $j++) {
                $section = new SectionsPages();
                $section->setTitre('Grand titre de la section numéro ' . $j);
                $section->setContenu("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.");
                $section->setPage($this->getReference("Page numéro " . $i-1));
                $manager->persist($section);
            }
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PagesFixtures::class];
    }
}
