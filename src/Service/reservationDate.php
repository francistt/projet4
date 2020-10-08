<?php
namespace App\Service;

class ReservationDate {
    feriesFixes: 
    -01/01
    -01/05
    -08/05
    -14/07
    -15/08
    -01/11
    -11/11
    -25/12
  
  feriesVariables:  #regarder http://www.informatix.fr/tutoriels/php/trouver-les-jours-feries-francais-en-php-137 pour les calculs
    paques: true
    ascension: true
    pentecote: true
  
  horaires:
    maxJournee: 12 #après 12h c'est forcément une demi journée
    maxResa: 18  #après 18h on ne peu plus réserver pour le jour même
  
  fermeture: #utiliser date("l")
    Tuesday
    Sunday
}
