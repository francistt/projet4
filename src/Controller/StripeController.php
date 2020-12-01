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
use Symfony\Component\Dotenv\Dotenv;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create/session/{uuid}", name="stripe_create_session", methods={"POST", "GET"})
     */
    public function index(EntityManagerInterface $manager, SessionManager $session, $uuid)
    {
        $key = $this->getParameter('app.api.key.stripe.test');

        Stripe::setApiKey($key);
        $YOUR_DOMAIN = $_ENV['DOMAIN'];

        $order = $manager->getRepository(Reservation::class)->findOneByUuid($uuid);

        if (!$order) {
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
                        'images' => [$YOUR_DOMAIN . '/image/Louvrestripe.jpg'],
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
