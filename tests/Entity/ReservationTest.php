<?php

namespace Tests\Entity;

use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;
use App\Service\ReservationDate;
use App\Repository\ReservationRepository;
use DateTime;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ReservationTest extends TestCase
{
    private $projectDir;
    private $reservationRepository;
    private $reservationDate;
    private $totalTicketReserved = 1;

    /**
     * SetUp pour les tests
     *
     * @return void
     */
    public function setUp(): void 
    {
        $this->projectDir = $this->createMock(ParameterBagInterface::class);
        $this->projectDir
        ->expects($this->any())
        ->method('get')
        ->willReturn('C:\Users\franc\Documents\symfony\projet4');

        $this->reservationRepository = $this->createMock(ReservationRepository::class);
        $this->reservationRepository->expects($this->any())
             ->method('getTotalTicket')
             ->willReturn($this->totalTicketReserved); 
      
    }

    /**
     * Test la fonction dateIsValid() : cas nombre maximal de clients atteint
     *
     * @return void
     */
    public function testFailedReservation()
    {
        $expected = "Le nombre maximum de visiteur est atteint";

        $this->totalTicketReserved = 995;
        $this->reservationRepository = $this->createMock(ReservationRepository::class);
        $this->reservationRepository->expects($this->any())
             ->method('getTotalTicket')
             ->willReturn($this->totalTicketReserved); 

        $this->reservationDate = new ReservationDate($this->projectDir, $this->reservationRepository);
        $reservation = new Reservation();
        $reservation->setReservationDate(new DateTime('2021-02-08'));
        $reservation->setNbTicket(10);

        $result = $this->reservationDate->dateIsValid($reservation);

        static::assertEquals($expected, $result['info']);

    }

    /**
     * Test la fonction dateIsValid() : cas nombre maximal de clients pas atteint
     *
     * @return void
     */
    public function testSuccessReservation()
    {
        $expectedResult = "";
    
        $this->reservationDate = new ReservationDate($this->projectDir, $this->reservationRepository);
        $reservation = new Reservation();
        $reservation->setReservationDate(new DateTime('2021-02-08'));
        $reservation->setNbTicket(10);

        $result = $this->reservationDate->dateIsValid($reservation);

        static::assertEquals($expectedResult, $result['info']);
    }

    /**
     * Test la fonction dateIsValid() : cas jour si dimanche ou lundi
     *
     * @return void
     */
    public function testDayOff()
    {
        $expectedResult = "Le musée est fermé le mardi et le dimanche";

        $this->reservationDate = new ReservationDate($this->projectDir, $this->reservationRepository);
        $reservation = new Reservation();
        $reservation->setReservationDate(new DateTime('2021-02-07'));

        $result = $this->reservationDate->dateIsValid($reservation);

        static::assertEquals($expectedResult, $result['info']);
    }
}
