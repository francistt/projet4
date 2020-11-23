<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationDate;
use App\Service\SessionManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"GET","POST"})
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request, EntityManagerInterface $manager, ReservationDate $reservationDate, ParameterBagInterface $parameterBag, SessionManager $session): Response
    {
        $request->getSession()->set('total', 0);    
        $reservation = new Reservation();
        //on récupère le formulaire
        
        $form = $this->createForm(ReservationType::class, $reservation);

        //on récupère les prix de config.yaml
        $projectDir          = $parameterBag->get('kernel.project_dir');
        $value               = Yaml::parseFile($projectDir . '/config/contraints/config.yaml');

        //on relie l'objet à la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resultat = $reservationDate->dateIsValid($reservation);
            if (!$resultat['open']) {
                $formError = new FormError($resultat['info']);
                $form->get('reservation_date')->addError($formError);
            } else {
                $reservation->setUuid(uniqid());
                $reservation->setIsPaid(0);
                //on enregistre en BDD
               
                $manager->persist($reservation);
                $manager->flush();
                //dd($form->get('reserver')->get('email')->getData());
                $session->setOrder([
                    "email" => $form->get('reserver')->get('email')->getData(),
                    "lastName" => $form->get('reserver')->get('lastName')->getData(),
                    "firstName" => $form->get('reserver')->get('firstName')->getData(),
                    "nbTicket" => $form->get('nbTicket')->getData()
                ]);
                //dd($session, $session->getData('email'));
                return $this->redirectToRoute('summary', ['uuid' => $reservation->getUuid()]);
            }
        }

        //on rend la vue
        return $this->render('reservation.html.twig', [
            'reservation'   => $reservation,
            'form'          => $form->createView(),
            'normal'        => $value['TicketPrice']['prices']['normal'],
            'children'      => $value['TicketPrice']['prices']['children'],
            'limitChildren' => $value['TicketPrice']['ages']['limitChildren'],
            'limitBaby'     => $value['TicketPrice']['ages']['limitBaby'],
            'senior'        => $value['TicketPrice']['prices']['senior'],
            'sinceSenior'   => $value['TicketPrice']['ages']['sinceSenior'],
            'discount'      => $value['TicketPrice']['prices']['discount'],
        ]);
    }
}
