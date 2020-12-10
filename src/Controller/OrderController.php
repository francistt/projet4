<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Entity\Reservation;
use App\Service\TicketPrice;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\SessionManager;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande/{uuid}", name="summary")
     */
    public function summarize(Reservation $reservation, Request $request, UserRepository $repository, EntityManagerInterface $manager, TicketPrice $ticketPrice, SessionManager $session)
    {
        $user = new User();
        $form = $this->createform(UserType::class, $user);

        //on relie l'objet à la requête
        $form->handleRequest($request);
        dd($form);
        $isFinish = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($reservation->getClients()->count() < $reservation->getNbTicket()) {
                $price = $ticketPrice->getPrice($user->getBirthdate(), $user->getDiscount(), $reservation->getHalfDay());
                $user->setPrice($price);
                $user->setReservation($reservation);

                $session->addTicket($price);
                $reservation->setTotal($session->getTotal());

                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('summary', ['uuid' => $reservation->getUuid()]);
            } else {
                $isFinish = true;
            }
        }

        return $this->render('order.html.twig', [
            'formUser' => $form->createView(),
            'reservation' => $reservation,
            'uuid' => $reservation->getUuid(),
            'limit' => $reservation->getNbTicket(),
            'is_finish' => $isFinish
        ]);
    }
}
