<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/admin', 'index')]
    public function homepage(): Response
    {
        $message = "Bienvenue sur l'interface d'administration de My_Website";
        return $this->render('/admin/homepage.html.twig', ['message' => $message]);
    }
}
