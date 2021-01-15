<?php

namespace App\Controller;

use App\Service\Mail;
use App\Entity\Reservation;
use App\Service\SessionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{idStripe}", name="order_validate")
     */
    public function index($idStripe, SessionManager $session, Reservation $reservation, Mail $mail)
    {
        $order = $this->entityManager->getRepository(Reservation::class)->findOneByIdStripe($idStripe);

        if (!$order) {
            return $this->redirectToRoute('hompage');
        }
        //dd($order, $session);

        if (!$order->getIsPaid()) {

            // Modifier le statut isPaid de notre commande en mettant 1
            $order->setIsPaid(1);
            $this->entityManager->flush();

            // Envoyer un email au client pour lui confirmer sa commande
            // $mail = new Mail();
            $content = $this->renderView('mail.html.twig', [
                'reservation' => $reservation,
                'session' => $session,
                'order' => $order,
                'reservationDate' => $session->getData('reservation_date'),
                'firstName' => $session->getData('firstName'),
                'lastName' => $session->getData('lastName'),
                'email' => $session->getData('email')
            ]);
            $mail->send($session->getData('email'), $session->getData('firstName'), "Vos billets", $content);
        }

        // Afficher les quelques informations de la commande de l'utilisateur

        return $this->render('success.html.twig', [
            'reservation' => $reservation,
            'session' => $session,
            'order' => $order,
            'reservationDate' => $session->getData('reservation_date'),
            'firstName' => $session->getData('firstName'),
            'lastName' => $session->getData('lastName'),
            'email' => $session->getData('email')
        ]);
    }
}
