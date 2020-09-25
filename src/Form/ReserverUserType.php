<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReserverUserType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
