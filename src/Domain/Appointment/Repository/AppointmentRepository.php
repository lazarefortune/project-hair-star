<?php

namespace App\Domain\Appointment\Repository;

use App\Domain\Appointment\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Appointment|null findOneBy( array $criteria, array $orderBy = null )
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, Appointment::class );
    }

    public function save( Appointment $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( Appointment $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
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
