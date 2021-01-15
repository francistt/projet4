<?php

namespace App\Service;

use DateTime;
use Symfony\Component\Yaml\Yaml;
// use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TicketPrice
{
    private $sinceSenior;
    private $limitBaby;
    private $limitChildren;
    private $priceSenior;
    private $priceBaby;
    private $priceChildren;
    private $priceNormal;
    private $discount;
    private $coefHalfDay;

    public function __construct()
    {    
        // $projectDir          = $parameterBag->get('kernel.project_dir');
        $value               = Yaml::parseFile(__DIR__. '/../../config/contraints/config.yaml');
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

    /**
     * Permet de calculer le prix en fonction de l'age
     *
     * @param DateTime $birthdate
     * @param boolean $reduced
     * @param boolean $halfday
     * @return integer
     */
    public function getPrice($birthdate, $reduced, $halfday)
    {
        $age = $this->getAge($birthdate);
        if ($age < $this->limitBaby)     return $this->priceBaby;
        if ($age < $this->limitChildren) return $this->definePrice($this->priceChildren, $reduced, $halfday);
        if ($age > $this->sinceSenior)   return $this->definePrice($this->priceSenior, $reduced, $halfday);

        return $this->definePrice($this->priceNormal, $reduced, $halfday);
    }
    
    /**
     * Permet de calculer l'age en fonction de la date de naissance
     *
     * @param DateTime $birthdate
     * @return integer
     */
    private function getAge($birthdate)
    {
        return $birthdate->diff(new DateTime())->y;
    }

    /**
     * Permet de calculer le prix unitaire en fonction du choix
     *
     * @param integer $price
     * @param boolean $reduced
     * @param boolean $halfday
     * @return integer
     */
    private function definePrice($price, $reduced, $halfday)
    { 
        if ($halfday) $price = $price * $this->coefHalfDay;
        if ($reduced) $price = $price - $this->discount;
        if ($price < 0) return 0;
        
        return $price;
    }
}
