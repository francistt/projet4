<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/premium")
 */
class PremiumController extends AbstractController
{
  /**
   * @Route("/", name="premium_index")
   * @return Response
   */
  public function indexAction()
  {
    return $this->render(':premium:index.html.twig');
  }

  /**
   * @Route("/verify", options={"expose"="true"}, name="premium_verify")
   * @param Request $request
   * @return Response
   */
  public function verifyAction(Request $request)
  {
    return $this->render(':premium:verify.html.twig');
  }

  /**
   * @Route("/payment", name="premium_payment")
   * @param Request $request
   * @return Response
   */
  public function paymentAction(Request $request)
  {
    $form = $this->get('form.factory')
        ->createNamedBuilder('payment-form')
        ->add('token', HiddenType::class, [
            'constraints' => [new NotBlank()],
        ])
        ->add('submit', SubmitType::class)
        ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        
            if ($form->isValid()) {
            // TODO: charge the card
            }
        }
        
        return $this->render(':stripe.html.twig', [
        'form' => $form->createView(),
        'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);  
  }
}