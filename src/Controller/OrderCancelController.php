<?php

namespace App\Controller;
use App\Service\Mail;
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
    public function index($idStripe, SessionManager $session, Mail $mail)
    {
        $order = $this->entityManager->getRepository(Reservation::class)->findOneByIdStripe($idStripe);
        if (!$order) {
            return $this->redirectToRoute('hompage');


        }
        // Envoyer un email à notre utilisateur pour lui indiquer l'échec de paiement     
          $content = $this->renderView('mailCancel.html.twig', [
            'order' => $order,
            'firstName' => $session->getData('firstName'),
        ]);
      
        $mail->send($session->getData('email'), $session->getData('firstName'), "Erreur de paiement", $content);
        




        return $this->render('cancel.html.twig', [
            'session' => $session,
            'order' => $order,
            'firstName' => $session->getData('firstName'),
            'lastName' => $session->getData('lastName'),
            'email' => $session->getData('email')
        ]);
    }
}
