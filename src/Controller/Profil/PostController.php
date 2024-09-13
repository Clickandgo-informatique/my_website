<?php

namespace App\Controller\Profil;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profil/posts', name: 'posts_')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostsRepository $postsRepo): Response
    {
        $posts = $postsRepo->findBy([],['created_at'=>'DESC']);
        return $this->render('profil/posts/index.html.twig', [
            'posts' => $posts
        ]);
    }
    #[Route('/creer', 'creer')]
    public function creerPost(PictureService $pictureService, EntityManagerInterface $em, Request $request, SluggerInterface $slugger, UsersRepository $usersRepo): Response
    {
        $post = new Posts();
        $titre = "Créer un nouvel article";
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug(strtolower($slugger->slug($post->getTitre())));
            $post->setUsers($this->getUser());


            $featuredImage = $form->get('featuredImage')->getData();
            $image = $pictureService->add($featuredImage, 'articles', 300,300);
            $post->setFeaturedImage($image);

            $em->persist($post);

            $em->flush();

            $this->addFlash('success', 'Le nouvel article a été posté avec succcès.');
            return $this->redirectToRoute('posts_index');
        }
        return $this->render('profil/posts/posts-form.html.twig', compact('form', 'titre'));
    }
}
