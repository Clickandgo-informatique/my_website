<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name:'index')]
    public function homepage(): Response
    {
        $message = "Bienvenue sur le site My_Website";
        return $this->render('admin/homepage.html.twig', ['message' => $message]);
    }
}
