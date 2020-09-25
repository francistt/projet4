<?php
namespace App\Service;

use DateTime;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TicketPrice {
    private $price;
    private $ageSenior;
    private $ageBaby;
    private $ageChildren;
    private $priceSenior;
    private $priceBaby;
    private $priceChildren;
    private $priceNormal;
    private $priceReduced;
    private $coefHalfPrice;

    public function __construct(ParameterBagInterface $parameterBag){
        $projectDir          = $parameterBag->get('kernel.project_dir');
        $value               = Yaml::parseFile($projectDir.'\config\contraints\config.yaml');
        $this->sinceSenior   = $value['TicketPrice']['ages']['sinceSenior'];
        $this->limitBaby     = $value['TicketPrice']['ages']['limitBaby'];
        $this->limitChildren = $value['TicketPrice']['ages']['limitChildren'];
        $this->priceSenior   = $value['TicketPrice']['prices']['senior'];
        $this->priceBaby     = $value['TicketPrice']['prices']['baby'];
        $this->priceChildren = $value['TicketPrice']['prices']['children'];
        $this->priceNormal   = $value['TicketPrice']['prices']['normal'];
        $this->discount      = $value['TicketPrice']['prices']['discount'];
        $this->coefHalfDay   = $value['TicketPrice']['coefficient']['halfDay'];
    }

    public function getPrice($birthdate, $reduced, $halfday){
        $age = $this->getAge($birthdate);
        if ($age < $this->limitBaby)     return $this->priceBaby;
        if ($age < $this->limitChildren) return $this->definePrice($this->priceChildren, $reduced, $halfday);
        if ($age > $this->sinceSenior)   return $this->definePrice($this->priceSenior, $reduced, $halfday);

        return $this->definePrice($this->priceNormal, $reduced, $halfday);
    }

    private function getAge($birthdate){
        return $birthdate->diff(new DateTime())->y;
    }

    private function definePrice($ref, $reduced, $halfday){
        $price = $ref;
        if ($halfday) $price = $price * $this->coefHalfDay;
        if ($reduced) $price = $price - $this->discount;
        if ($price < 0) return 0;
        
        return $price;
    }
}