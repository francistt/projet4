<?php

namespace Tests\Service;

use DateTime;
use App\Service\TicketPrice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class TicketTest extends TestCase {

    /**
     * Test si la date de naissance d'un senior renvoi le bon prix
     *
     * @return void
     */
    public function testGetPriceSenior()
    {
        $value = Yaml::parseFile(__DIR__.'/../../config/contraints/config.yaml');
        $expectedResult = $value['TicketPrice']['prices']['senior'];

        $reduced = false;
        $halfday = false;
        $birthdate = new DateTime('1940-01-01');

        $ticket = new TicketPrice();
        $result = $ticket->getPrice($birthdate, $reduced, $halfday);
        
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test si la date de naissance d'un enfant renvoi le bon prix
     *
     * @return void
     */
    public function testGetPriceChildren()
    {
        $value = Yaml::parseFile(__DIR__.'/../../config/contraints/config.yaml');
        $expectedResult = $value['TicketPrice']['prices']['children'];

        $reduced = false;
        $halfday = false;
        $birthdate = new DateTime('2015-01-01');

        $ticket = new TicketPrice();
        $result = $ticket->getPrice($birthdate, $reduced, $halfday);
        
        $this->assertEquals($expectedResult, $result);
    }
}
