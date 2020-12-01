<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\SessionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/erreur/{idStripe}", name="order_cancel")
     */
    public function index($idStripe, SessionManager $session)
    {
        $order = $this->entityManager->getRepository(Reservation::class)->findOneByIdStripe($idStripe);
        //dd($order);
        if (!$order) {
            return $this->redirectToRoute('hompage');
        }

        // Envoyer un email à notre utilisateur pour lui indiquer l'échec de paiement

        return $this->render('cancel.html.twig', [
            'session' => $session,
            'order' => $order,
            'firstName' => $session->getData('firstName'),
            'lastName' => $session->getData('lastName'),
            'email' => $session->getData('email')
        ]);
    }
}
