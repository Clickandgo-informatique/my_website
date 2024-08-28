<?php

namespace App\Controller;

use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('repositionnement-images', 'repositionnement_images')]
    public function repositionnerimages(Request $request, imagesRepository $imagesRepo, EntityManagerInterface $em): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {

            $tblPositions = json_decode($request->getContent(), false);

            foreach ($tblPositions as $image) {

                $imageActive = $imagesRepo->findOneBy(['id' => $image->id]);
                $imageActive->setOrdre($image->position);

                $em->persist($imageActive);
            }

            $em->flush();
            $this->addFlash('success', 'Le repositionnement de images a été enregistré avec succès dans la base.');
        } else {
            return new JsonResponse('Erreur : Cette page doit être accédée en Ajax');
        }
        return new JsonResponse('Le nouvel ordre des images a été enregistré avec succès dans la base', 200);
    }

    
}
