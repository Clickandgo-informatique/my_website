<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(JWTService $jwt, SendEmailService $mail, Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // générer  un token
            $header = [
                'type' => 'JWT',
                'alg' => 'HS256'
            ];
            $payload = [
                'user_id' => $user->getId()
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            $mail->send(
                'no-reply@my_website.fr',
                $user->getEmail(),
                "Activation de votre compte sur My_website.com",
                'register',
                compact('user', 'token')
            );

            $this->addFlash('success','L\'utilisateur a été créé dans la base. Veuillez cliquer sur le lien d\'activation pour confirmer votre adresse email et activer le compte.');
            return $security->login($user, UsersAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verif/{token}', 'verify_user')]
    public function verifUser($token, JWTService $jwt, UsersRepository $usersRepo, EntityManagerInterface $em): Response
    {
        //Vérifier si token est valide
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            //On récupère le payload
            $payload = $jwt->getPayload($token);
            //On récupère l'user 
            $user = $usersRepo->find($payload['user_id']);
            //On vérifie si il est déjà activé
            if ($user && !$user->isVerified()) {
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_main');
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré.');
        return $this->redirectToRoute('app_login');
    }
}
