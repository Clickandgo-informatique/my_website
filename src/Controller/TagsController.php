<?php

namespace App\Controller;

use App\Entity\Galeries;
use App\Entity\Tags;
use App\Repository\GaleriesRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/tags/', name: 'tags_')]
class TagsController extends AbstractController
{

    #[Route('liste-tags', name: 'liste-tags')]
    public function listeTags(TagsRepository $tagsRepo): Response
    {
        $listeTags = $tagsRepo->findBy([], ['titre' => 'ASC']);

        return $this->render('_partials/liste-tags.html.twig', ['listeTags' => $listeTags]);
    }
    #[Route('tags-galeries', name: 'tags_galeries')]
    public function tagsGaleries(SessionInterface $session,TagsRepository $tagsRepo, Request $request,GaleriesRepository $galeriesRepo): Response
    {
        $galerieId=$session->get('galerieId');
        $listeTags = $tagsRepo->findBy(['parent' => 'galeries'], ['titre' => 'ASC']);

        $tags = [];

        foreach ($listeTags as $key => $tag) {
            $tags[$key]['id'] = $tag->getId();
            $tags[$key]['titre'] = $tag->getTitre();
            $tags[$key]['icone'] = $tag->getIcone();
            $tags[$key]['couleur'] = $tag->getCouleur();
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($tags);
        } else {
            return new JsonResponse('La requête doit être effectuée en Ajax', 201);
        }
    }
    #[Route('tags-galeries/selection', name: 'tags_galeries_selection')]
    public function tagsGaleriesSelection(GaleriesRepository $galeriesRepo, Request $request, SessionInterface $session): Response
    {
        $galerieId = $session->get('galerieId');
        $galerie=$galeriesRepo->find($galerieId);
        $listeTags = $galerie->getTags();     

        $tags = [];

        foreach ($listeTags as $key => $tag) {
            $tags[$key]['id'] = $tag->getId();
            $tags[$key]['titre'] = $tag->getTitre();
            $tags[$key]['icone'] = $tag->getIcone();
            $tags[$key]['couleur'] = $tag->getCouleur();
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($tags);
        } else {
            return new JsonResponse('La requête doit être effectuée en Ajax', 201);
        }
    }
    #[Route('tags-images', name: 'tags_images')]
    public function tagsImages(TagsRepository $tagsRepo, Request $request): Response
    {
        $listeTags = $tagsRepo->findBy(['parent' => 'images'], ['titre' => 'ASC']);

        $tags = [];

        foreach ($listeTags as $key => $tag) {
            $tags[$key]['id'] = $tag->getId();
            $tags[$key]['titre'] = $tag->getTitre();
            $tags[$key]['icone'] = $tag->getIcone();
            $tags[$key]['couleur'] = $tag->getCouleur();
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($tags);
        } else {
            return new JsonResponse('La requête doit être effectuée en Ajax', 201);
        }
    }
    #[Route("sauvegarde-tags", "sauvegarde_tags")]
    public function sauvegardeTags(Request $request, SessionInterface $session, GaleriesRepository $galeriesRepo, EntityManagerInterface $em, TagsRepository $tagsRepo): Response
    {
        $postData = json_decode($request->getContent());

        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);

        if ($request->isXmlHttpRequest()) {

            foreach ($postData as $tag) {
                $tag = $tagsRepo->find($tag->id);
                $galerie->addTag($tag);
                $em->persist($galerie);
            }
            $em->flush();
            return new JsonResponse('Tags enregistrés avec succès dans la base', 200);
        } else {
            return new JsonResponse(['error' => "Cette requête doit s'effectuer en Ajax."], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('effacer-tags', 'effacer_tags')]
    public function effacertags(EntityManagerInterface $em, GaleriesRepository $galeriesRepo, SessionInterface $session, Request $request, TagsRepository $tagsRepo): Response
    {
        $postData = json_decode($request->getContent());
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);
        $tag = $tagsRepo->find($postData->id);

        if ($request->isXmlHttpRequest()) {
            $galerie->removeTag($tag);
            $em->persist($galerie);
            $em->flush();

            return new JsonResponse('Le tag a été effacé avec succès de la base');
        } else {
            return new JsonResponse(['error' => "La requête doit être effectuée en Ajax."], Response::HTTP_BAD_REQUEST);
        }
    }
}
