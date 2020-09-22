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
     * @Route("/home", name="home")
     */
    public function home() {
        return $this->render('ticket/home.html.twig');
    }

    /**
     * @Route("/home/user", name="user")
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
