<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(private readonly SluggerInterface $slugger) {}
    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'titre' => 'France',
                'parent' => null
            ],
            [
                'titre' => 'Monde',
                'parent' => null
            ],
            [
                'titre' => 'Politique',
                'parent' => 'France'
            ],
            [
                'titre' => 'Associations',
                'parent' => 'France'
            ],
            [
                'titre' => 'Economie',
                'parent' => 'Monde'
            ]
        ];

            foreach($categories as $category){   
                
                $newcategory = new Categories();
                $newcategory->setTitre($category['titre']);
    
                $slug = strtolower($this->slugger->slug($newcategory->getTitre()));
    
                $newcategory->setSlug($slug);
    
                // On crée une référence à cette catégorie
                $this->setReference($category['titre'], $newcategory);
    
                $parent = null;
    
                // On vérifie si la catégorie a un parent dans le tableau
                if($category['parent'] !== null){
                    $parent = $this->getReference($category['parent']);
                }
    
                $newcategory->setParent($parent);
                
                $manager->persist($newcategory);

        }
        $manager->flush();
    }
}
