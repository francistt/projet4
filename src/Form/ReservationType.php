<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' =>"Nom",
                'attr' => [
                    'placeholder' => 'votre nom'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' =>"Prénom",
                'attr' => [
                    'placeholder' => 'votre prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' =>"Adresse Email",
                'attr' => [
                    'placeholder' => 'votre email'
                ]
            ])
            ->add('nbTicket', IntegerType::class,[
                'label' =>"Nombre de billet(s)"
            ])


            ->add('reservation_date', DateTimeType::class,[
                'label' =>"Date de réservation",
                'widget' => 'single_text'
            ])
            
            ->add('halfDay', ChoiceType::class, [
                'label' =>"Type de billet",
                'choices' => array_flip([
                    'Billet « Journée »',
                    'Billet « Demi-journée ». Il vous permet d’entrer dans l’établissement à partir de 14h.'
                ]),
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
