<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager,Cart $cart, $reference) : Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $productObject = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'currency' => 'eur',
                'amount' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'name' => $product->getProduct(),
                'images' => [$YOUR_DOMAIN."/uploads/".$productObject->getIllustration()],
            ];
        }

        $products_for_stripe[] = [
            'currency' => 'eur',
            'amount' => $order->getCarrierPrice(),
            'quantity' => 1,
            'name' => $order->getCarrierName(),
            'images' => [$YOUR_DOMAIN],
        ];


        Stripe::setApiKey('sk_test_51LHt5jC1jgtXrLqefZujmtSbghPJ5G1lJLZtQQY9vunl8Df5TaVY7WAvhEKkig12wdvYH7WzzElH5DzfJyJYSaLP00IjRv8VTh');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN.'/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN.'/commande/erreur/{CHECKOUT_SESSION_ID}'
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }

}
