<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');

            // Envoi un mail
            $mail = new Mail();
            $mail->send('djaroul@gmail.com', 'La boutique francaise', 'Mail contact', 'Vous avez recu un mail de '.$form->get('prenom')->getData().' '.$form->get('nom')->getData().', avec de contenu '.$form->get('content')->getData());
            //ou
            // gardes les infos du mail dans une nouvelle entité
            //ou
            // relier à une api de suivi de commande par exmple (zendesk)
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
