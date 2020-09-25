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
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('reserver', ReserverUserType::class, [
               'label' => false
           ])
            ->add('nbTicket', IntegerType::class,[
                'label' =>"Nombre de billet(s)"
            ])


            ->add('reservation_date', DateType::class,[
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
