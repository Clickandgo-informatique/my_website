<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Form\PagesType;
use App\Repository\PagesRepository;
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

        dump($listePages);
        return $this->render('admin/liste-pages.html.twig', ['listePages' => $listePages]);
    }

    #[Route('modifier/{id}', 'modifier')]
    public function modifier(PagesRepository $pagesRepo, $id, Request $request, EntityManagerInterface $em): Response
    {
        $page = $pagesRepo->find($id);
        $pageId = $id;
        // dd($pageId);
        $titre = "Modifier une page";
        $form = $this->createForm(PagesType::class, $page, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($page);
            $em->flush();
        }

        return $this->render('admin/pages-form.html.twig', ['form' => $form, 'titre' => $titre, 'pageId' => $pageId]);
    }
    #[Route('creer', 'creer')]
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
