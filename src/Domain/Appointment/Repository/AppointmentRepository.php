<?php

namespace App\Domain\Appointment\Repository;

use App\Domain\Appointment\Entity\Appointment;
use App\Infrastructure\Orm\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends AbstractRepository<Appointment>
 */
class AppointmentRepository extends AbstractRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, Appointment::class );
    }

    public function findAllOrderedByDate() : array
    {
        return $this->createQueryBuilder( 'b' )
            ->orderBy( 'b.date', 'DESC' )
            ->addOrderBy( 'b.time', 'DESC' )
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Appointment[]
     */
    public function findReservedAppointments() : array
    {
        return $this->createQueryBuilder( 'b' )
            ->andWhere( 'b.status = :val' )
            ->setParameter( 'val', 'confirmed' )
            ->orderBy( 'b.date', 'ASC' )
            ->addOrderBy( 'b.time', 'ASC' )
            ->getQuery()
            ->getResult();
    }
}
