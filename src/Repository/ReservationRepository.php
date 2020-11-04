<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function getTotalTicket(DateTimeInterface $dateTimeInterface)
    {
        $qb = $this->createQueryBuilder('r');
        $qb
           ->select('SUM(r.nbTicket) AS nb_ticket')
           ->where('r.reservation_date = :date')
           ->setParameter('date', $dateTimeInterface->format('Y-m-d'));
           
        return (int) $qb->getQuery()->getResult()[0]['nb_ticket'];
    }
}
