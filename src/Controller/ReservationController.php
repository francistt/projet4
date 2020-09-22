<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/home", name="reservation_home", methods={"GET","POST"})
     */
    public function home(Request $request, EntityManagerInterface $manager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setUuid(date("YMdHis"),uniqid('', true));

            $manager->persist($reservation);
            $manager->flush();
            
            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/home.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
}
