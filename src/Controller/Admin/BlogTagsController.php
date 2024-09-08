<?php

namespace App\Controller\Admin;

use App\Entity\BlogTags;
use App\Form\BlogTagsType;
use App\Repository\BlogTagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/blog/tags/', 'blog_tags_')]
class BlogTagsController extends AbstractController
{
    #[Route('liste', name: 'liste')]
    public function index(BlogTagsRepository $blogtagsRepo): Response
    {
        $tags = $blogtagsRepo->findBy([], ['titre' => 'ASC']);
        return $this->render('admin/blog/liste-tags.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('ajouter', 'ajouter')]
    public function ajouterBlogTag(SluggerInterface $slugger, Request $request, EntityManagerInterface $em): Response
    {
        $tag = new BlogTags();
        $form = $this->createForm(BlogTagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setSlug(strtolower($slugger->slug(($tag->getTitre()))));
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Le mot clé a bien été ajouté à la base de données');
            return $this->redirectToRoute('blog_tags_liste');
        }

        return $this->render('admin/blog/tags-form.html.twig', ['form' => $form]);
    }

    #[Route('modifier/{id}', 'modifier')]
    public function modifierTag(Request $request, $id, BlogTagsRepository $blogtagsRepo, EntityManagerInterface $em): Response
    {
        $tag = $blogtagsRepo->find($id);
        $form = $this->createForm(BlogTagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('blog_tags_liste');
            $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
        }
        return $this->render('admin/blog/tags-form.html.twig', compact('form'));
    }
    #[Route('supprimer/{id}', 'supprimer')]
    public function SupprimerTag(Request $request, $id, BlogTagsRepository $blogtagsRepo, EntityManagerInterface $em)
    {
        if ($request->isXmlHttpRequest()) {

            $tag = $blogtagsRepo->find($id);
            $em->remove($tag);
            $em->flush();

            return $this->redirectToRoute('blog_tags_liste');
            $this->addFlash('success', 'Les modifications ont été correctement effectuées dans la base de données');
        } else {
            return new JsonResponse('Cette requête doit être effectuée en Ajax.');
        }
    }
}
