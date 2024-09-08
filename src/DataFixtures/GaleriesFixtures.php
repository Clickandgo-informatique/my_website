<?php

namespace App\DataFixtures;

use App\Entity\Galeries;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GaleriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $typesGaleries = ["Statique horizontale", "Statique verticale", "Diaporama"];
        for ($i = 0; $i < 20; $i++) {
            $galerie = new Galeries();
            $galerie->setTitre("Galerie n° " . $i + 1)
                ->setDescription("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.")
                ->setType($faker->randomElement($typesGaleries))
                ->setIsActive(true)
                ->setPage($this->getReference("Page numéro " . rand(0, 4)));
            $this->addReference("Galerie numéro " . $i + 1, $galerie);

            $manager->persist($galerie);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PagesFixtures::class];
    }
}
