<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Entity\SectionsPages;
use App\Form\SectionsPagesType;
use App\Repository\PagesRepository;
use App\Repository\SectionsPagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/pages/sections/', 'pages_')]
class SectionsPagesController extends AbstractController
{

    #[Route('liste-sections', 'liste_sections')]
    public function listeSectionsPages(SectionsPagesRepository $sectionsPagesRepo, PagesRepository $pagesRepo): Response
    {

        $listeSections = $sectionsPagesRepo->findBy([], ['created_at' => 'desc']);

        return $this->render('admin/liste-sections-pages.html.twig', ['listeSections' => $listeSections]);
    }

    #[Route('modifier-section/{id}', 'modifier_section')]
    public function modifier(SectionsPagesRepository $sectionsPagesRepo, $id, Request $request, EntityManagerInterface $em): Response
    {
        $section = $sectionsPagesRepo->find($id);
        $titre = "Modifier une section de page";
        $form = $this->createForm(SectionsPagesType::class, $section, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($section);
            $em->flush();

            $this->addFlash('success', 'La section de page a été modifiée avec succès dans la base.');
            return $this->redirectToRoute('pages_liste_sections');
        }

        return $this->render('admin/sections-pages-form.html.twig', ['form' => $form, 'titre' => $titre]);
    }
    #[Route('creer-section/{pageId}', 'creer_section')]
    public function creer(Request $request, EntityManagerInterface $em, $pageId, PagesRepository $pagesRepo): Response
    {

        $section = new SectionsPages();
        $page = $pagesRepo->find($pageId);

        $titre = "Créer une section de page";
        $form = $this->createForm(SectionsPagesType::class, $section, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $section->setPage($page);

            $em->persist($section);
            $em->flush();

            $this->addFlash('message', "La nouvelle section de page a été créée avec succès.");
            return $this->redirectToRoute('pages_liste_sections', ['pageId' => $pageId]);
        }

        return $this->render('admin/sections-pages-form.html.twig', ['form' => $form, 'titre' => $titre, 'pageId' => $pageId]);
    }
}
