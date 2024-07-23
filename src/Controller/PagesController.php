<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Form\PagesType;
use App\Repository\PagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/pages/', 'pages_')]
class PagesController extends AbstractController
{

    #[Route('liste-pages', 'liste_pages')]
    public function listePages(PagesRepository $pagesRepo): Response
    {
        $listePages = $pagesRepo->findBy([], ['created_at' => 'desc']);

        return $this->render('admin/liste-pages.html.twig', ['listePages' => $listePages]);
    }

    #[Route('modifier-page/{id}', 'modifier_page')]
    public function modifier(Pages $existingpage, PagesRepository $pagesRepo, $id, Request $request, EntityManagerInterface $em): Response
    {
        $sections = new ArrayCollection();
        foreach ($existingpage->getSectionsPages() as $section) {
            $sections->add($section);
        }
       
        $page = $pagesRepo->find($id);
        $pageId = $id;

        $titre = "Modifier une page";
        $form = $this->createForm(PagesType::class, $page, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //traitement des sections de pages supprimées
            // foreach ($sections as $section) {
            //     if (false === $page->getSectionsPages()->contains($section)) {
            //         $section->getPage()->removeElement($page);
            //         $em->remove($section);
            //     }
            // }
        
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', 'La page a été sauvegardée avec succès dans la base.');
        }

        return $this->render('admin/pages-form.html.twig', ['form' => $form, 'titre' => $titre, 'pageId' => $pageId]);
    }
    #[Route('creer-page', 'creer_page')]
    public function creer(Request $request, EntityManagerInterface $em): Response
    {
        $page = new Pages();
        $titre = "Créer une page";
        $form = $this->createForm(PagesType::class, $page, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($page);
            $em->flush();

            $this->addFlash('message', "La nouvelle page a été créée avec succès.");
            return $this->redirectToRoute('pages_liste');
        }

        return $this->render('admin/pages-form.html.twig', ['form' => $form, 'titre' => $titre]);
    }
}
