<?php

namespace Tests\Service;

use DateTime;
use App\Service\TicketPrice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class TicketTest extends TestCase {


    //public function testTestAreWorking()
    //{
    //    $this->assertEquals(2, 1+1);
    //}

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
}
