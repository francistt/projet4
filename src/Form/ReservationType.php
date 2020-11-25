<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('reserver', ReserverUserType::class, [
               'label' => false
           ])
            ->add('nbTicket', IntegerType::class,[
                'label' =>"Nombre de billet(s) :",
                    'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 1
                ]
            ])

            ->add('reservation_date', TextType::class,[
                'label' =>"Date de visite :"           
            ])
            
            ->add('halfDay', ChoiceType::class, [
                'label' =>"Type de billet :",
                'choices' => array_flip([
                    'Billet « Journée »',
                    'Billet « Demi-journée » Il vous permet d’entrer dans l’établissement à partir de 14h.'
                ]),
                'expanded' => true
            ])
        ;

        $builder->get('reservation_date')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
