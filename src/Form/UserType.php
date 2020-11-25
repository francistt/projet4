<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('country', CountryType::class, [
                'preferred_choices' => ['FR'],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'address.form.country.placeholder'
                ],
            ])
            ->add('birthdate', BirthdayType::class, [
                'format'      => 'ddMMyyyy',
                'years'       => range(date('Y') - 99, date('Y')),
                'label_attr'  => ['class' =>'active'],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour', ],
                'attr'        => [
                    'class' => 'active birthdate',
                ],
                'required'   => true
            ])
            ->add('discount', CheckboxType::class, [
                'required' => false
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
