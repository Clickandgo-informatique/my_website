<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Form\PagesType;
use App\Repository\GroupesLinksRepository;
use App\Repository\LinksRepository;
use App\Repository\PagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/pages/', 'pages_')]
class PagesController extends AbstractController
{

    #[Route('liste-pages', 'liste_pages')]
    public function listePages(PagesRepository $pagesRepo): Response
    {
        $listePages = $pagesRepo->findBy([], ['ordre' => 'ASC']);

        return $this->render('admin/liste-pages.html.twig', ['listePages' => $listePages]);
    }

    #[Route('modifier-page/{id}', 'modifier_page')]
    public function modifier(Pages $pages, PagesRepository $pagesRepo, $id, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $sections = new ArrayCollection();
        foreach ($pages->getSectionsPages() as $section) {
            $sections->add($section);
        }

        $currentpage = $pagesRepo->find($id);
        $pageId = $id;
        $session->set('pageId', $pageId);
        $titre = "Modifier une page";
        $form = $this->createForm(PagesType::class, $currentpage, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //traitement des sections de pages en cours de suppression
            foreach ($sections as $section) {
                if (false === $pages->getSectionsPages()->contains($section)) {
                    $em->remove($section);
                }
            }

            $em->persist($currentpage);
            $em->flush();

            $this->addFlash('success', 'La page a été sauvegardée avec succès dans la base.');
        }

        return $this->render('admin/pages-form.html.twig', ['form' => $form, 'titre' => $titre, 'pageId' => $pageId, 'page' => $currentpage]);
    }

    #[Route('creer-page', 'creer_page')]
    public function creer(Request $request, EntityManagerInterface $em, SessionInterface $session, PagesRepository $pagesRepo): Response
    {
        $page = new Pages();
        $titre = "Créer une page";
        $form = $this->createForm(PagesType::class, $page, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $position = $pagesRepo->getLastPagePosition();
            $page->setOrdre($position + 1);
            $em->persist($page);
            $em->flush();
            $pageId = $page->getId();
            $session->set('pageId', $pageId);

            $this->addFlash('message', "La nouvelle page a été créée avec succès.");
            return $this->redirectToRoute('pages_modifier_page', ['id' => $pageId]);
        }

        return $this->render('admin/pages-form.html.twig', ['form' => $form, 'titre' => $titre]);
    }

    #[Route('choisir-page-d-accueil', 'choisir_page_accueil')]
    public function choisirPageAccueil(PagesRepository $pagesRepo, SessionInterface $session, Request $request, EntityManagerInterface $em): Response
    {

        if ($request->isXmlHttpRequest()) {

            //On récupère l'état d'activation
            $is_homepage = $request->getContent();
            if ($is_homepage === "true") {
                //On efface la condition "page d'accueil" de toutes les pages
                $pages = $pagesRepo->findAll();

                foreach ($pages as $page) {
                    $page->setPageAccueil(false);
                }
                //On attribue la condition "page d'accueil" à la page en cours
                //On récupère l'id de la page active de nouveau
                $pageId = $session->get('pageId');
                $page = $pagesRepo->find($pageId);
                $page->setPageAccueil(true);
                $em->persist($page);
                $em->flush();

                $this->addFlash('success', "La page " . $page->getTitre() . " est devenue la page d'accueil");
                return new JsonResponse("La page " . $page->getTitre() . " est devenue la page d'accueil");
            } else {

                //On retire la condition "page d'accueil" à la page en cours
                //On récupère l'id de la page active de nouveau
                $pageId = $session->get('pageId');
                $page = $pagesRepo->find($pageId);
                $page->setPageAccueil(false);
                $em->persist($page);
                $em->flush();

                $this->addFlash('success', "La page " . $page->getTitre() . "  n'est plus la page d'accueil");
                return new JsonResponse("La page " . $page->getTitre() . "  n'est plus la page d'accueil");
            }
        } else {
            return new JsonResponse('Cette requête doit être effectuée en Ajax.', 404);
        }
        return new JsonResponse("Update effectué", 200);
    }

    #[Route('previsualiser-page', 'previsualiser_page')]
    public function previsualiserPage(SessionInterface $session, PagesRepository $pagesRepo): Response
    {
        $pageId = $session->get('pageId');
        $page = $pagesRepo->find($pageId);

        return $this->render('admin/previsualiser-page.html.twig', ['page' => $page]);
    }

    #[Route('afficher-links-pages-publiees', 'afficher_links_pages_publiees')]
    public function afficherLinksPagesPubliees(PagesRepository $pagesRepo, Request $request): Response
    {
        $pages = $pagesRepo->findBy(['etat' => 'publiee'], ['ordre' => 'ASC']);
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['pages' => $pages]);
        } else {
            return $this->render('_partials/_links-pages-publiees.html.twig', ['pages' => $pages]);
        }
    }

    #[Route('afficher-links-footer', 'afficher_links_footer')]
    public function afficherLinksFooter(LinksRepository $linksRepo, GroupesLinksRepository $groupesLinksRepo): Response
    {
        $links = $linksRepo->findBy(['parent' => 'footer']);
        $groupesLinks = $groupesLinksRepo->findBy([], ['titre' => 'ASC']);
        return $this->render('_partials/_footer.html.twig', ['links' => $links, 'groupesLinks' => $groupesLinks]);
    }

    #[Route('repositionnement-pages', 'repositionnement_pages')]
    public function repositionnerPages(Request $request, PagesRepository $pagesRepo, EntityManagerInterface $em): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {

            $tblPositions = json_decode($request->getContent(), false);

            foreach ($tblPositions as $page) {

                $pageActive = $pagesRepo->findOneBy(['id' => $page->id]);
                $pageActive->setOrdre($page->position);

                $em->persist($pageActive);
            }

            $em->flush();
            $this->addFlash('success', 'Le repositionnement de pages a été enregistré avec succès dans la base.');
        } else {
            return new JsonResponse('Erreur : Cette page doit être accédée en Ajax');
        }
        return new JsonResponse('Le nouvel ordre des pages a été enregistré avec succès dans la base', 200);
    }
}
