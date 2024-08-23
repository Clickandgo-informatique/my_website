<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(Security $security){}
    #[Route(path: 'admin/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
        //  if($this->security->isGranted('ROLE_ADMIN'));  
        //      return $this->redirectToRoute('accueil_admin');
        // }else{
        return $this->redirectToRoute('index');
    }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('mot-de-passe-oublie', 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository,
        JWTService $jwt,
        SendEmailService $mail
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $usersRepository->findOneBy(['email' => $form->get("email")->getData()]);
            if ($user) {
                // générer  un token
                $header = [
                    'type' => 'JWT',
                    'alg' => 'HS256'
                ];
                $payload = [
                    'user_id' => $user->getId()
                ];

                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $mail->send(
                    'no-reply@my_website.fr',
                    $user->getEmail(),
                    "Récupération de votre mot de passe sur My_website.com",
                    'password-reset',
                    compact('user', 'url')
                );
                $this->addFlash('success', 'Email de récupération de mot de passe envoyé avec succès,veuillez vérifier votre boîte mail.');
                return $this->redirectToRoute('app_login');
            } else {

                $this->addFlash('danger', 'un problème est survenu');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/reset-password-request.html.twig', [
            'requestPasswordform' => $form
        ]);
    }
    #[Route('mot-de-passe-oublie/{token}', 'reset_password')]
    public function resetPassword(
        EntityManagerInterface $em,
        $token,
        JWTService $jwt,
        UsersRepository $usersRepo,
        UserPasswordHasherInterface $passwordHasher,
        Request $request
    ): Response {
        //Vérifier si token est valide
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            //On récupère le payload
            $payload = $jwt->getPayload($token);
            //On récupère l'user 
            $user = $usersRepo->find($payload['user_id']);
            //On vérifie si il est déjà activé
            if ($user) {
                $form = $this->createForm(ResetPasswordFormType::class);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
                    $em->flush();

                    $this->addFlash('success', 'Votre nouveau mot de passe a été correctement enregistré dans la base.');
                    return $this->redirectToRoute('app_login');
    
                }
                return $this->render('security/reset-password.html.twig',['passForm'=>$form]);           
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré.');
        return $this->redirectToRoute('app_login');
    }
}
