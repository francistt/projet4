<?php

namespace App\Controller;

use App\Entity\Reservation;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Service\SessionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create/session/{uuid}", name="stripe_create_session", methods={"POST", "GET"})
     */
    public function index(EntityManagerInterface $manager, SessionManager $session, $uuid)
    {
        Stripe::setApiKey('sk_test_51HeLh9Ahufd1YJu1368gsHaeysM6kOR2UkCERDqYGrqCj4CLPQcSpBFUaKxvtxsRUxlNHHW3KDFyd30rEkiJs8my00P9En7Ihd');
        $YOUR_DOMAIN = 'http://127.0.0.1:8001';

        $order = $manager->getRepository(Reservation::class)->findOneByUuid($uuid);

        if(!$order) {
            new JsonResponse(['error' => 'reservation']);
        }
        //dd($session);
        $checkout_session = Session::create([
            'customer_email' => $session->getData('email'),
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $session->getData('total') * 100,
                    'product_data' => [
                        'name' => 'Billet(s) pour le musée du Louvre',
                        'images' => ["https://lh3.googleusercontent.com/proxy/87PjhvzRBcd7htJ4_1m6B_IMYnyl5foUdKYqn6ZA0SUpwEhCXbkmVjFxZLF9h_7uQd5UrQAjOyHpxIUrfJXMW6SuspTc5sHrjPiXPuWqqPxp9iOobHyOA6OHQOeNk_YIIzhnxRmq6qsjF0Y0gkioThCxoZxUBCKpqUsxIBGP14bVY5SOOQD79jaQEEc_ZxyQv1XjtMd6NZ6v-WQfoTxufQ-mmcSmNhgKU0-V4gXlbCH7rZDNPOBptAPUC7i4g_MFXIzHvwznWOMWoCwtzwdlVqY5BXk3TD2R6CgpnDFtAvui_-0aXSVZZZYB8zXWbw"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setIdStripe($checkout_session->id);
        //dd($order, $checkout_session, $session->getTotal());
        $manager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
