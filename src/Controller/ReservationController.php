<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\ReservationDate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/home", name="reservation_home", methods={"GET","POST"})
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request, EntityManagerInterface $manager, ReservationDate $reservationDate): Response {
        $reservation = new Reservation();

        //on récupère le formulaire
        $form = $this->createForm(ReservationType::class, $reservation);

        //on relie l'objet à la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resultat = $reservationDate->dateIsValid($reservation->getReservationDate());
            if (!$resultat['open']) {
                $formError = new FormError($resultat['info']);
                $form->get('reservation_date')->addError($formError);
            } else  {
                $reservation->setUuid(uniqid());
                //on enregistre en BDD
                $manager->persist($reservation);
                $manager->flush();
                
                return $this->redirectToRoute('summary',['uuid'=>$reservation->getUuid()]);
            }
        }

        //on rend la vue
        return $this->render('reservation/home.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
}
