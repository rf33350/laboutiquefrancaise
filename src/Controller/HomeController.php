<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_home')]
    public function index(SessionInterface $session): Response
    {
        //$mail = new Mail();
        //$mail->send('djaroul@gmail.com','John Doe','Test mail MailJet','Bonjour, si ca marche c\'est mon tout premier mail que j\'envoie avec symfony. C\'est trop de la balle!!!2')

        $products = $this->entityManager->getRepository(Product::class)->findByisBest(true);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $headers,
        ]);
    }
}
