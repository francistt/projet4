<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Entity\Reservation;
use App\Service\TicketPrice;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{

    /**
     * @Route("/user/{uuid}", name="summary")
     */
    public function summarize(Reservation $reservation, Request $request, UserRepository $repository, EntityManagerInterface $manager, TicketPrice $ticketPrice): Response 
    {
        $user = new User();
        $form = $this->createform(UserType::class, $user);

        //on relie l'objet à la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $price = $ticketPrice->getPrice($user->getBirthdate(), $user->getDiscount(), $reservation->getHalfDay());
           $user->setPrice($price); 
           $user->setReservation($reservation);


           $manager->persist($user);
           $manager->flush();

           if ($reservation->getClients()->count() == $reservation->getNbTicket()) {
               //$request->getSession()->set('payer', true);
               //return $this->render('stripe.html.twig');
           }
           else {
                return $this->redirectToRoute('summary',['uuid'=>$reservation->getUuid()]);
           }
        }

        return $this->render('ticket/contactInfos.html.twig', [
            'formUser' => $form->createView(),
            'reservation' => $reservation,
            'limit' => $reservation->getNbTicket(),
        ]);
    }

    /**
     * @Route("/stripe", name="stripe", methods={"POST", "GET"})
     */
    public function stripe(Request $request)
    {
        $amount = $request->get('total');
        return $this->render('stripe.html.twig');
        // on récupere $discount la date 
    }
}
