<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/categories/', name: 'categories_')]
class CategoriesController extends AbstractController
{
        #[Route('gestion-categories', 'gestion-categories')]
        public function index(CategoriesRepository $categoriesRepo, Request $request): Response
        {
    
            $parent = json_decode($request->getContent());
            $listeCategories = $categoriesRepo->findBy(['parent' => $parent], ['titre' => 'ASC']);
    
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['content' => $this->renderView('_partials/_liste-categories.html.twig', ['listeCategories' => $listeCategories])]);
            } else {
                return $this->render('admin/categories/gestion-categories.html.twig');
            }
        }
    
        #[Route('creer', 'creer')]
        public function creerCategorie(SluggerInterface $slugger, Request $request, EntityManagerInterface $em): Response
        {
            $categorie = new Categories();
            $form = $this->createForm(CategoriesType::class, $categorie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $categorie->setSlug(strtolower($slugger->slug(($categorie->getTitre()))));
                $em->persist($categorie);
                $em->flush();
    
                $this->addFlash('success', 'Le mot clé a bien été ajouté à la base de données');
            }
    
            return $this->render('admin/categories/categories-form.html.twig', ['form' => $form]);
        }
    
        #[Route('modifier/{id}', 'modifier')]
        public function modifierCategorie(Request $request, $id, CategoriesRepository $categoriesRepo, EntityManagerInterface $em): Response
        {
            $categorie = $categoriesRepo->find($id);
            $form = $this->createForm(CategoriesType::class, $categorie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($categorie);
                $em->flush();
    
                return $this->redirectToRoute('categories_liste-categories');
                $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
            }
            return $this->render('admin/categories/categories-form.html.twig', compact('form'));
        }
        #[Route('supprimer/{id}', 'supprimer')]
        public function SupprimerTag(Request $request, $id, CategoriesRepository $categoriesRepo, EntityManagerInterface $em)
        {
            if ($request->isXmlHttpRequest()) {
    
                $categorie = $categoriesRepo->find($id);
                $em->remove($categorie);
                $em->flush();
    
                return $this->redirectToRoute('categories_liste-categories');
                $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
            } else {
                return new JsonResponse('Cette requête doit être effectuée en Ajax.');
            }
        }
}
