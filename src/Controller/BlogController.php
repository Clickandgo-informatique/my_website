<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/blog', 'blog_')]
class BlogController extends AbstractController
{
    #[Route('/','index')]
    public function index(): Response
    {
        return $this->render('admin/blog/index.html.twig');
    }
}
