<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\SessionManager;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create/session", name="stripe_create_session", methods={"POST", "GET"})
     */
    public function index(SessionManager $session)
    {
        Stripe::setApiKey('sk_test_51HeLh9Ahufd1YJu1368gsHaeysM6kOR2UkCERDqYGrqCj4CLPQcSpBFUaKxvtxsRUxlNHHW3KDFyd30rEkiJs8my00P9En7Ihd');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $session->getTotal() * 100,
                    'product_data' => [
                        'name' => 'Billet pour le musée du Louvre',
                        'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
