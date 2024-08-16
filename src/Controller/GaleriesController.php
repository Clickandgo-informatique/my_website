<?php

namespace App\Controller;

use App\Entity\Galeries;
use App\Entity\Images;
use App\Form\GaleriesType;
use App\Repository\GaleriesRepository;
use App\Repository\ImagesRepository;
use App\Repository\TagsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

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
    public function creer(EntityManagerInterface $em, Request $request, PictureService $pictureService, SessionInterface $session, TagsRepository $tagsRepo): Response
    {
        $listeTags = $tagsRepo->findBy(['parent' => 'Galeries'], ['titre' => 'ASC']);
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

        return $this->render('admin/galeries-form.html.twig', ['form' => $form, 'titre' => $titre, 'galerie' => $galerie, 'tags' => $listeTags]);
    }

    #[Route('modifier-galerie/{id}', 'modifier_galerie', methods: ['GET', 'POST'])]
    public function modifier(PictureService $pictureService, EntityManagerInterface $em, GaleriesRepository $galeriesRepo, Request $request, $id, SessionInterface $session, TagsRepository $tagsRepo): Response
    {
        $galerie = $galeriesRepo->find($id);
        $listeTags = $tagsRepo->findBy(['parent' => 'Galeries'], ['titre' => 'ASC']);

        //Enregistrement de l'id de galerie pour requête Ajax
        $session->set('galerieId', $galerie->getId());

        $titre = "Modifier une galerie d'images";
        $form = $this->createForm(GaleriesType::class, $galerie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($request->getContent());
            // dump($request->request->get('listeImages'));
            // dump($request->query->get('listeImages'));
            // die();

            //Traitement des images    

            $images = $form->get('images')->getData();
            // dd($images);

            foreach ($images as $img) {
                $folder = 'images';
                $fichier = $pictureService->add($img, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $galerie->addImage($img);
            }
            $em->persist($galerie);
            $em->flush();

            $this->addFlash('success', 'La nouvelle galerie d\'images a été actualisée dans la base.');
        }

        return $this->render('admin/galeries-form.html.twig', ['form' => $form, 'titre' => $titre, 'galerie' => $galerie, 'tags' => $listeTags]);
    }

    #[Route('supprimer-galerie/{id}', 'supprimer_galerie', methods: ['DELETE'])]
    public function supprimer(Request $request, Galeries $galerie, EntityManagerInterface $em, $id): Response
    {
        $request->enableHttpMethodParameterOverride();
        if ($this->isCsrfTokenValid('delete' . $galerie->getId($id), $request->request->get('_token'))) {
            $em->remove($galerie);
            $em->flush();

            return $this->redirectToRoute('galeries_liste_galeries');
        }
    }

    #[Route('supprimer-image/{id}', 'supprimer_image', methods: ['GET', 'DELETE'])]
    public function supprimerImage(Images $image, Request $request, EntityManagerInterface $em): Response
    {

        $request->enableHttpMethodParameterOverride();
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
    #[Route('afficher-galerie-images', 'afficher_galerie_images')]
    public function afficherImages(ImagesRepository $ImagesRepo, GaleriesRepository $galeriesRepo, SessionInterface $session): Response
    {
        //Récupération de l'Id de la galerie en cours      
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);

        // if ($request->isXmlHttpRequest()) {
        $images = $ImagesRepo->findBy(['galerie' => $galerie]);

        return new JsonResponse(['content' => $this->renderView('_partials/_galerie.html.twig', ['images' => $images])]);
        // }
    }
    //Affichage ajax des images dans la galerie en création (carousel)
    #[Route('afficher-carousel-images', 'afficher_carousel_images')]
    public function afficherCarousel(ImagesRepository $ImagesRepo, GaleriesRepository $galeriesRepo, SessionInterface $session): Response
    {
        //Récupération de l'Id de la galerie en cours      
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);

        // if ($request->isXmlHttpRequest()) {
        $images = $ImagesRepo->findBy(['galerie' => $galerie]);

        return new JsonResponse(['content' => $this->renderView('_partials/_carousel.html.twig', ['images' => $images])]);
        // }
    }
    //Affichage ajax des miniatures d'images en horizontal)
    #[Route('afficher-miniatures-horizontale/{galerieId}', 'afficher_miniatures_horizontale')]
    public function afficherMiniatures(ImagesRepository $ImagesRepo, GaleriesRepository $galeriesRepo, int $galerieId): Response
    {
        //Récupération de l'Id de la galerie en cours 
        $galerie = $galeriesRepo->find($galerieId);

        // if ($request->isXmlHttpRequest()) {
        $images = $ImagesRepo->findBy(['galerie' => $galerie]);

        return new JsonResponse(['content' => $this->renderView('_partials/_miniatures-horizontale.html.twig', ['images' => $images])]);
        // }
    }
    //Affichage des galeries par select
    #[Route('visionneuse-galeries', 'visionneuse_galeries')]
    public function visionneuseGaleries(GaleriesRepository $galeriesRepo): Response
    {
        $listeGaleries = $galeriesRepo->findBy([], ['titre' => 'ASC']);

        return $this->render('admin/visionneuse-galeries.html.twig', ['listeGaleries' => $listeGaleries]);
        // }
    }
    #[Route('afficher-galerie-visionneuse/{galerieId}', 'afficher_galerie_visionneuse')]
    public function afficherGalerieVisionneuse(ImagesRepository $ImagesRepo, GaleriesRepository $galeriesRepo, $galerieId, Request $request): Response
    {
        //Récupération de l'Id de la galerie en cours       

        $galerie = $galeriesRepo->find($galerieId);
        $typeGalerie = $galerie->getType();

        if ($request->isXmlHttpRequest()) {
            $images = $ImagesRepo->findBy(['galerie' => $galerie]);

            if ($typeGalerie === "galerie") {
                return new JsonResponse(['content' => $this->renderView('_partials/_galerie.html.twig', ['images' => $images])]);
            }
            if ($typeGalerie === "carousel") {
                return new JsonResponse(['content' => $this->renderView('_partials/_carousel.html.twig', ['images' => $images])]);
            }
        }
        return new JsonResponse(['error' => 'Cet appel doit être effectué via AJAX.'], Response::HTTP_BAD_REQUEST);
    }
    
    #[Route('importer-images', 'importer_images', methods: ['POST'])]
    public function importerImages(Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        if ($request->isXmlHttpRequest()) {
            $postData = json_decode($request->getContent(), FALSE);
            //dd($postData);
            $galerie = $postData->galerieId;
            // dd($galerie);
            $listeImages = $postData->files;
            //dd($listeImages);
            foreach ($listeImages as $image) {
                $image = new UploadedFile("", $image, $image->name, $image->type);
                $folder = 'images';
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $galerie->addImage($img);
            }
            $em->persist($galerie);
            $em->flush();

            $this->addFlash('success', 'La nouvelle galerie d\'images a été actualisée dans la base.');
            return new JsonResponse('Réponse en json');
        }
        return new JsonResponse(['error' => 'Cet appel doit être effectué via AJAX.'], Response::HTTP_BAD_REQUEST);
    }
}
