<?php

namespace App\Controller\Admin;

use App\Entity\Links;
use App\Form\LinksType;
use App\Repository\LinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/links/', 'links_')]
class LinksController extends AbstractController
{
    #[Route('liste-links', 'liste_links')]
    public function afficherLinks(LinksRepository $linksRepo): Response
    {
        $links = $linksRepo->findBy([], ['titre' => 'ASC']);
        return $this->render('admin/liste-links.html.twig', ['links' => $links]);
    }

    #[Route('creer-link', 'creer_link')]
    public function creerLink(Request $request, EntityManagerInterface $em): Response
    {
        $link = new Links();

        $form = $this->createForm(LinksType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($link);
            $em->flush();
        }

        return $this->render('admin/links-form.html.twig', ['form' => $form]);
    }
    #[Route('modifier-link/{id}', 'modifier_link')]
    public function modifierLink(LinksRepository $linksRepo, $id, Request $request, EntityManagerInterface $em): Response
    {
        $link = $linksRepo->find($id);
        $form = $this->createForm(LinksType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($link);
            $em->flush();
        }

        return $this->render('admin/links-form.html.twig', ['form' => $form]);
    }
    #[Route('supprimer-link/{id}', 'supprimer_link')]
    public function supprimerLink(LinksRepository $linksRepo, $id, Request $request, EntityManagerInterface $em): Response
    {
        $link = $linksRepo->find($id);

        $em->remove($link);
        $em->flush();

        $this->addFlash('alert success', 'Le link a été supprimé de la base avec succès.');


        return $this->redirectToRoute('links_liste_links');
    }
}
