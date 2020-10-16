<?php

namespace App\Service;

use DateTimeInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ReservationDate
{

  private $publicHoliday;

  public function __construct(ParameterBagInterface $parameterBag)
  {
    $projectDir          = $parameterBag->get('kernel.project_dir');

    $value             = Yaml::parseFile($projectDir . '\config\contraints\config.yaml');
    $this->publicHoliday = $value["Holidays"];
    $this->closedDay = $value["closedDay"];
  }

  public function dateIsValid(DateTimeInterface $date)
  {
    // check public holiday
    $dayMonth = $date->format('d/m');
    if (in_array($dayMonth, $this->getPublicHoliday())) {
      return ['open' => false, 'info' => 'Le musée est fermé les jours fériées'];
    } 
    // if ($date->format('l') === 'Sunday' || $date->format('l') === 'Tuesday') {
    //   return ['open' => false, 'info' => 'Le musée est fermé le mardi et le dimanche'];
    // }

    if (array_search($date->format("l"),$this->closedDay, true) !== false) {
      return ['open' => false, 'info' => 'Le musée est fermé le mardi et le dimanche'];
    }

    if ($this->paques($date->format("Y"),$date->format("m"),$date->format("d"))) {
      return ['open' => false, 'info' => 'Le musée est fermé les jours fériés'];

    }

    return ['open' => true, 'info' => ''];
  }

  public function getPublicHoliday()
  {
    return $this->publicHoliday;
  }


/**
 * vérifie que la date demandée n'est pas le jour de pâques
 *
 * @param   [type]  $year          $année de la réservation
 * @param   [type]  $monthBooking  [$monthBooking description]
 * @param   [type]  $dayBooking    [$dayBooking description]
 *
 * @return  bool                 [return description]
 */
  private function paques($year, $monthBooking, $dayBooking)
  {
    $year = intval($year);
    $a = $year % 4;
    $b = $year % 7;
    $c = $year % 19;
    $m = 24;
    $n = 5;
    $d = (19 * $c + $m) % 30;
    $e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;

    $easterdate = 22 + $d + $e;

    if ($easterdate > 31) {
      $day = $d + $e - 9;
      $month = 4;
    } else {
      $day = 22 + $d + $e;
      $month = 3;
    }

    if ($d == 29 && $e == 6) {
      $day = 10;
      $month = 04;
    } elseif ($d == 28 && $e == 6) {
      $day = 18;
      $month = 04;
    }

    if ($day === intval($dayBooking) && $month === intval($monthBooking)) return true;
    elseif (($day + 1) === intval($dayBooking) && $month === intval($monthBooking)) return true;
    elseif (($day + 9) === intval($dayBooking) && ($month + 1) === intval($monthBooking)) return true;
    elseif (($day - 12) === intval($dayBooking) && ($month + 2) === intval($monthBooking)) return true;
    elseif (($day - 11) === intval($dayBooking) && ($month + 2) === intval($monthBooking)) return true;
    return false;
  }


  

  /* 
  horaires:
    maxJournee: 14 #après 14h c'est forcément une demi journée
    maxResa: 18  #après 18h on ne peu plus réserver pour le jour même
  */
}
