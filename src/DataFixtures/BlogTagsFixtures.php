<?php

namespace App\DataFixtures;

use App\Entity\BlogTags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogTagsFixtures extends Fixture
{
    public function __construct(private readonly SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $tags=['France','Monde','Politique','Economie','Associations'];

        foreach($tags as $tag){
        $newTag = new BlogTags();
        $newTag->setTitre($tag);
        $slug=strtolower($this->slugger->slug($newTag->getTitre()));
        $newTag->setSlug($slug);
        $manager->persist($newTag);
        }
        $manager->flush();
    }
}
