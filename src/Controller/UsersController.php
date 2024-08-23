<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/utilisateurs/', 'utilisateurs_')]
class UsersController extends AbstractController
{

    #[Route('liste-utilisateurs', 'liste_utilisateurs')]
    public function listeUtilisateurs(UsersRepository $usersRepo): Response
    {
        $users = $usersRepo->findAllUser('["ROLE_USER"]');
        return $this->render('admin/liste-utilisateurs.html.twig', ['users' => $users]);
    }
}
