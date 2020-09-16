<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Users;

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
    public function contactInfo(Request $request, EntityManagerInterface $manager) {
        dump($request);
        return $this->render('ticket/contactInfo.html.twig');
    }

        /**
     * @Route("/billetterie/contacts", name="contacts")
     */
    public function newUser(Request $request, EntityManagerInterface $manager) {
        $users = new Users();

        $form = $this->createFormBuilder($users)
                     ->add('lastName')
                     ->add('firstName')
                     ->add('birthdate')
                     ->add('country')
                     ->getForm();

        $form->handleRequest($request);

        //$manager->persist($users);
        //$manager->flush();

        return $this->render('ticket/contactInfos.html.twig', [
            'formUser' => $form->createView()

        ]);
    }
}
