<?php

namespace App\Controller;

use App\Entity\ImagesCategories;
use App\Form\ImagesCategoriesType;
use App\Repository\ImagesCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImagesCategoriesController extends AbstractController
{

    #[Route('admin/categories-images/liste', 'liste_categories_images')]
    public function liste(ImagesCategoriesRepository $categories)
    {
        $liste = $categories->findAll([], ['nom' => 'Asc']);

        return $this->render('admin/liste-categories-images.html.twig', ['liste' => $liste]);
    }


    #[Route('admin/categories-images/creer-categorie', 'creer_categorie_images')]
    public function creer(EntityManagerInterface $em, Request $request)
    {
        $categorie = new ImagesCategories();
        $titre = "Créer une catégorie d'images";
        $form = $this->createForm(ImagesCategoriesType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie d'images a été enregistrée avec succès dans la base");
            return $this->redirectToRoute('liste_categories_images');
        }

        return $this->render('admin/creer-categorie-images.html.twig', ['form' => $form, "titre" => $titre]);
    }

    #[Route('admin/categories-images/modifier-categorie/{id}', 'modifier_categorie_images')]
    public function modifier(EntityManagerInterface $em, Request $request, $id, ImagesCategoriesRepository $repo)
    {
        $categorie = $repo->find($id);
        $titre = "Modifier une catégorie d'images";
        $form = $this->createForm(ImagesCategoriesType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie d'images a été modifiée avec succès dans la base");
            return $this->redirectToRoute('liste_categories_images');
        }

        return $this->render('admin/creer-categorie-images.html.twig', ['form' => $form, "titre" => $titre]);
    }
    #[Route('admin/categories-images/supprimer-categorie/{id}', 'supprimer_categorie_images')]
    public function supprimer(EntityManagerInterface $em, Request $request, $id, ImagesCategoriesRepository $repo)
    {
        $categorie = $repo->find($id);
        $titre = "Supprimer une catégorie d'images";
  
            $em->remove($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie d'images a été supprimée avec succès dans la base");
            return $this->redirectToRoute('liste_categories_images');
        } 
    
}
