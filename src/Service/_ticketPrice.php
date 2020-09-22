<?php
namespace App\Service;
use Symfony\Component\Yaml\Yaml;

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

    public function __construct(){
        $value               = Yaml::parseFile(__DIR__.'/../../config.yaml');
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
        if ($age < $this->limitBaby)     return $priceBaby;
        if ($age < $this->limitChildren) return $this->definePrice($this->priceChildren, $reduced, $halfday);
        if ($age > $this->sinceSenior)   return $this->definePrice($this->priceSenior, $reduced, $halfday);
        return $this->definePrice($this->priceNormal, $reduced, $halfday);
    }

    private function getAge($birthdate){
        $age = date('Y') - $birthdate; 
        if (date('md') < date('md', strtotime($birthdate))) { 
            return $age - 1; 
        } 
        return $age;
    }

    private function definePrice($ref, $reduced, $halfday){
        $price = $ref;
        if ($halfday) $price = $price * $this->coefHalfDay;
        if ($reduced) $price = $price - $this->discount;
        if ($price < 0) return 0;
        return $price;
    }
}