<?php

namespace App\Controller;

use App\Repository\TagsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagsController extends AbstractController
{
    #[Route('/admin/tags', name: 'tags_liste-tags')]
    public function index(TagsRepository $tagsRepo): Response
    {
        $listeTags = $tagsRepo->findBy([], ['titre' => 'ASC']);
        return $this->render('admin/liste-tags.html.twig', [
            'listeTags' => $listeTags
        ]);
    }
}
