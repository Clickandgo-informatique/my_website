<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactFormController extends AbstractController
{

    #[Route('admin/formulaire-contact', 'formulaire_contact')]
    public function formulaireContact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = new TemplatedEmail();
            $email->to("mail@clickandgo-informatique.com")
                ->from($form->get('email')->getData())
                ->subject($form->get('sujet')->getData())
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'sujet'=>$form->get('sujet')->getData(),
                    'e_mail' => $form->get('email')->getData(),
                    'message' => $form->get('message')->getData()
                ]);
            $mailer->send($email);
            $this->addFlash('success', 'Votre mail a été envoyé avec succès, nous y répondrons le plus rapidement possible.');
            return $this->redirectToRoute('formulaire_contact');
        }
        return $this->render('emails/contact-form.html.twig', compact('form'));
    }
}
