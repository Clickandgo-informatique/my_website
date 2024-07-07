<?php

namespace App\Controller;

use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/images/', 'images_')]
class ImagesController extends AbstractController
{
    #[Route('liste-images', 'liste_images')]
    public function liste(ImagesRepository $imagesRepo): Response
    {
        $listeImages = $imagesRepo->findAll();

        return $this->render('admin/liste-images.html.twig', ['listeImages' => $listeImages]);
    }
}
