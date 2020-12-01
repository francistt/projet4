<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTime;
use DateTimeZone;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ReservationDate
{

  private $publicHoliday;
  private $reservationRepository;
  private $value;

  public function __construct(ParameterBagInterface $parameterBag, ReservationRepository $reservationRepository)
  {
    $projectDir        = $parameterBag->get('kernel.project_dir');
    $value             = Yaml::parseFile($projectDir . '/config/contraints/config.yaml');
    $this->value = $value;
    $this->publicHoliday = $value["Holidays"];
    $this->closedDay = $value["ClosedDay"];
    $this->bookingHourMax = $value["BookingHourMax"];
    $this->reservationRepository = $reservationRepository;
  }

  /**
   * Permet de vérifier si une date demandée est possible
   *
   * @param Reservation $reservation
   * @return Array
   */
  public function dateIsValid(Reservation $reservation)
  {
    $date = $reservation->getReservationDate()->setTimezone(new DateTimeZone('Europe/Paris'));
    $today = new DateTime('', new DateTimeZone('Europe/Paris'));
    $nbTicket = $reservation->getNbTicket();

    // check public holiday
    $dayMonth = $date->format('d/m');
    if (in_array($dayMonth, $this->getPublicHoliday())) {
      return ['open' => false, 'info' => 'Le musée est fermé les jours fériées'];
    }

    if (array_search($date->format("l"), $this->closedDay, true) !== false) {
      return ['open' => false, 'info' => 'Le musée est fermé le mardi et le dimanche'];
    }

    if ($this->paques($date->format("Y"), $date->format("m"), $date->format("d"))) {
      return ['open' => false, 'info' => 'Le musée est fermé les jours fériés'];
    }

    if ($date->format('Ymd') === (new \DateTime())->format('Ymd') && intval($date->format('H:i')) >= $this->bookingHourMax) {
      return ['open' => false, 'info' => 'Vous ne pouvez plus réserver pour le jour même'];
    }

    $nbTicketTotal = $this->reservationRepository->getTotalTicket($date) + $nbTicket;

    if ($nbTicketTotal >= $this->value['EntriesLimit']) {
      return ['open' => false, 'info' => 'Le nombre maximum de visiteur est atteint'];
    }

    if (
      $today->format('d/m/Y') === $date->format('d/m/Y')
      && '14:00:00' <= $date->format('H:i:s')
      && !$reservation->getHalfDay()
    ) {
      return ['open' => false, 'info' => "Demi tarif applicable après 14h"];
    }

    return ['open' => true, 'info' => ''];
  }

  /**
   * Retourne les dates des jours fériés
   *
   * @return void
   */
  public function getPublicHoliday()
  {
    return $this->publicHoliday;
  }

  /**
   * Vérifie que la date demandée n'est pas le jour de pâques
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
    if (($day + 1) === intval($dayBooking) && $month === intval($monthBooking)) return true;
    if (($day + 9) === intval($dayBooking) && ($month + 1) === intval($monthBooking)) return true;
    if (($day - 12) === intval($dayBooking) && ($month + 2) === intval($monthBooking)) return true;
    if (($day - 11) === intval($dayBooking) && ($month + 2) === intval($monthBooking)) return true;
    return false;
  }
}
