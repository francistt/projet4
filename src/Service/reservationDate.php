<?php
namespace App\Service;

use DateTimeInterface;

class ReservationDate {
    public function dateIsValid(DateTimeInterface $date)
    {
        // check public holiday
        $dayMonth = $date->format('d/m');
        if (in_array($dayMonth, $this->getPublicHoliday())) {
            return ['open' => false, 'info' => 'Le musée est fermé les jours fériées'];
        } elseif ($date->format('l') === 'Sunday' || $date->format('l') === 'Tuesday') {
            return ['open' => false, 'info' => 'Le musée est fermé le mardi et le dimanche']; 
        } 
        
        return ['open' => true, 'info' => ''];

    }

    public function getPublicHoliday()
    {
        return [
            '01/01',
            '01/05',
            '08/05',
            '14/07',
            '15/08',
            '01/11',
            '11/11',
            '25/12'
        ];
    }
  /* 
  
  feriesVariables:  #regarder http://www.informatix.fr/tutoriels/php/trouver-les-jours-feries-francais-en-php-137 pour les calculs
    paques: true
    ascension: true
    pentecote: true
  
  horaires:
    maxJournee: 14 #après 14h c'est forcément une demi journée
    maxResa: 18  #après 18h on ne peu plus réserver pour le jour même
  
  fermeture: #utiliser date("l")
    Tuesday
    Sunday*/
}
