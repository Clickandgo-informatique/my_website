<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\GaleriesRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/tags/', name: 'tags_')]
class TagsController extends AbstractController
{
    #[Route('gestion-tags', 'gestion-tags')]
    public function index(TagsRepository $tagsRepo, Request $request): Response
    {

        $parent = json_decode($request->getContent());
        $listeTags = $tagsRepo->findBy(['parent' => $parent], ['titre' => 'ASC']);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['content' => $this->renderView('_partials/_liste-tags.html.twig', ['listeTags' => $listeTags])]);
        } else {
            return $this->render('admin/tags/gestion-tags.html.twig');
        }
    }

    #[Route('creer', 'creer')]
    public function creerTag(SluggerInterface $slugger, Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setSlug(strtolower($slugger->slug(($tag->getTitre()))));
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Le mot clé a bien été ajouté à la base de données');
        }

        return $this->render('admin/tags/tags-form.html.twig', ['form' => $form]);
    }

    #[Route('modifier/{id}', 'modifier')]
    public function modifierTag(Request $request, $id, TagsRepository $tagsRepo, EntityManagerInterface $em): Response
    {
        $tag = $tagsRepo->find($id);
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tags_liste-tags');
            $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
        }
        return $this->render('admin/tags/tags-form.html.twig', compact('form'));
    }
    #[Route('supprimer/{id}', 'supprimer')]
    public function SupprimerTag(Request $request, $id, TagsRepository $tagsRepo, EntityManagerInterface $em)
    {
        if ($request->isXmlHttpRequest()) {

            $tag = $tagsRepo->find($id);
            $em->remove($tag);
            $em->flush();

            return $this->redirectToRoute('tags_liste-tags');
            $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
        } else {
            return new JsonResponse('Cette requête doit être effectuée en Ajax.');
        }
    }

    #[Route('tags-galeries', name: 'tags_galeries')]
    public function tagsGaleries(TagsRepository $tagsRepo, Request $request, SessionInterface $session, GaleriesRepository $galeriesRepo): Response
    {
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);
        $selectedTags = $galerie->getTags();
        $arraySelectedTags = [];

        foreach ($selectedTags as $key => $selectedTag) {
            $arraySelectedTags[$key] = $selectedTag->getId();
        }
        $listeTags = $tagsRepo->getAllGaleriesTags($arraySelectedTags);
     
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
            return new JsonResponse(['erreur' => "La requête doit être effectuée en Ajax", Response::HTTP_BAD_REQUEST]);
        }
    }

    #[Route('tags-galeries/selection', name: 'tags_galeries_selection')]
    public function tagsGaleriesSelection(GaleriesRepository $galeriesRepo, Request $request, SessionInterface $session): Response
    {
        $galerieId = $session->get('galerieId');
        $galerie = $galeriesRepo->find($galerieId);
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
