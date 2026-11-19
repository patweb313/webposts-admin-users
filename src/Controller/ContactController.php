<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Email
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Request;
use App\Class\Contact;
use App\Form\ContactType;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $email = new Email()
            ->from($contact->getEmail())
            ->to('noreply@webpost.be')
            ->subject($contact->getSubject())
            ->text($contact->getMessage());
        
        $mailer->send($email); 

        return $this->redirectToRoute('app_home');
        }
        
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
        
    }
}
