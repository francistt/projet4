<?php

namespace Tests\Form;

use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testGoodData()
    {
        $form = $this->factory->create(UserType::class);

        $form->submit([
            'id' => 200,
            'lastName' => 'Dupont',
            'firstName' => 'Jean',
            'email' => 'jean.dupont@gmail.com',
            'country' => 'FR',
            'birthdate' => '1990-10-12',
            'discount' => 1,
            'reservation' => 100,
            'price' => 12
        ]);

        static::assertTrue($form->isSubmitted()
        );

        static::assertSame([
                'id' => null,
                'lastName' => 'Dupont',
                'firstName' => 'Jean',
                'email' => null,
                'country' => 'FR',
                'birthdate' => null,
                'discount' => true,
                'reservation' => null,
                'price' => null
            ],
            $form->getData()
        );
    }
}