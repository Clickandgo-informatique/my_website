<?php

namespace App\Controller;

use App\Entity\Galeries;
use App\Entity\Images;
use App\Form\GaleriesType;
use App\Repository\GaleriesRepository;
use App\Repository\ImagesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/galeries/', 'galeries_')]
class GaleriesController extends AbstractController
{
    #[Route('liste-galeries', 'liste_galeries')]
    public function liste(GaleriesRepository $galeriesRepo): Response
    {
        $liste = $galeriesRepo->findAll([], ['titre' => 'Asc']);
        return $this->render('admin/liste-galeries.html.twig', ['liste' => $liste]);
    }

    #[Route('creer-galerie', 'creer_galerie', methods: ['GET', 'POST'])]
    public function creer(EntityManagerInterface $em, Request $request, PictureService $pictureService, SessionInterface $session): Response
    {
        $galerie = new Galeries();
        $titre = "Créer une galerie d'images";
        $session->set('galerieId', null);
        $form = $this->createForm(GaleriesType::class, $galerie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Traitement des images
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $folder = 'images';
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $img = new Images();
                $img->setName($fichier);
                $galerie->addImage($img);
            }
            $galerie->setActive(true);
            $em->persist($galerie);
            $em->flush();

            //Enregistrement de l'id de galerie pour requête Ajax
            $session->set('galerieId', $galerie->getId());


            $this->addFlash('success', 'La nouvelle galerie d\'images a été créée dans la base.');
            // return $this->redirectToRoute('galeries_liste_galeries');
        }

        return $this->render('admin/galeries-form.html.twig', ['form' => $form->createView(), 'titre' => $titre, 'galerie' => $galerie]);
    }

    #[Route('modifier/{id}', 'modifier_galerie', methods: ['GET', 'POST'])]
    public function modifier(PictureService $pictureService, EntityManagerInterface $em, GaleriesRepository $galeriesRepo, Request $request, $id, SessionInterface $session): Response
    {
        $galerie = $galeriesRepo->find($id);

        //Enregistrement de l'id de galerie pour requête Ajax
        $session->set('galerieId', $galerie->getId());

        $titre = "Modifier une galerie d'images";
        $form = $this->createForm(GaleriesType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //Traitement des images
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $folder = 'images';
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $galerie->addImage($img);
            }
            $em->persist($galerie);
            $em->flush();

            $this->addFlash('success', 'La nouvelle galerie d\'images a été actualisée dans la base.');
        }

        return $this->render('admin/galeries-form.html.twig', ['form' => $form->createView(), 'titre' => $titre, 'galerie' => $galerie]);
    }

    #[Route('supprimer/{id}', 'supprimer_galerie', methods: ['DELETE'])]
    public function supprimer(Request $request, Galeries $galerie, EntityManagerInterface $em, $id): Response
    {
        $request->enableHttpMethodParameterOverride();
        if ($this->isCsrfTokenValid('delete' . $galerie->getId(), $request->request->get('_token'))) {
            $em->remove($galerie);
            $em->flush();

            return $this->redirectToRoute('galeries_liste_galeries');
        }
    }

    #[Route('supprimer-image/{id}', 'supprimer_image',methods:['GET','DELETE'])]
    public function supprimerImage(Images $image, Request $request, EntityManagerInterface $em): Response
    {
        $request->enableHttpMethodParameterOverride();
        dd($request->request->getString('_token'));
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $nom = $image->getName();
            unlink($this->getParameter('images_directory') . '/' . $nom);
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }

    //Affichage ajax des images dans la galerie en création
    #[Route('afficher-images', 'afficher_images')]
    public function afficherImages(ImagesRepository $ImagesRepo, GaleriesRepository $galeriesRepo, SessionInterface $session): Response
    {
        //Récupération de l'Id de la galerie en cours
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);

        // if ($request->isXmlHttpRequest()) {
        $images = $ImagesRepo->findBy(['galerie' => $galerie]);

        return new JsonResponse(['content' => $this->renderView('admin/_partials/_galerie.html.twig', ['images' => $images])]);
        // }
    }
}
