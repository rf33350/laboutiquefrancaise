<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SessionInterface $session): Response
    {
        //$mail = new Mail();
        //$mail->send('djaroul@gmail.com','John Doe','Test mail MailJet','Bonjour, si ca marche c\'est mon tout premier mail que j\'envoie avec symfony. C\'est trop de la balle!!!2');


        return $this->render('home/index.html.twig');
    }
}
