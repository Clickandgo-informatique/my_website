<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarouselController extends AbstractController{
    #[Route('admin/carousel','carousel')]
    public function afficher():Response
    {
        return $this->render('admin/_partials/_carousel.html.twig');
    }
}