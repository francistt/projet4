<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;
use App\Form\UserType;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index() {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }

    /**
     * @Route("/billetterie", name="home")
     */
    public function home() {
        return $this->render('ticket/home.html.twig');
    }

        /**
     * @Route("/billetterie/contact", name="contact")
     */
    public function newUser(Request $request, EntityManagerInterface $manager) {
        $user = new User();

        $form = $this->createform(UserType::class, $user);

        $form->handleRequest($request);

        //$manager->persist($user);
        //$manager->flush();

        return $this->render('ticket/contactInfos.html.twig', [
            'formUser' => $form->createView()

        ]);
    }
}
