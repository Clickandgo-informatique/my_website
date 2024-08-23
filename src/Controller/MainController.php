<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('admin/accueil-admin', name: 'accueil_admin')]
    public function index(): Response
    {
        return $this->render('admin/accueil-admin.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
