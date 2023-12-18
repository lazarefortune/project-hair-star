<?php

namespace App\Domain\Holiday\Repository;

use App\Domain\Holiday\Entity\Holiday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Holiday>
 *
 * @method Holiday|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Holiday|null findOneBy( array $criteria, array $orderBy = null )
 * @method Holiday[]    findAll()
 * @method Holiday[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class HolidayRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, Holiday::class );
    }

    public function save( Holiday $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( Holiday $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Holiday>
     */
    public function findHolidayBetweenDates( \DateTimeInterface $startDate, \DateTimeInterface $endDate ) : array
    {
        return $this->createQueryBuilder( 'h' )
            ->andWhere( 'h.startDate BETWEEN :startDate AND :endDate' )
            ->orWhere( 'h.endDate BETWEEN :startDate AND :endDate' )
            ->setParameter( 'startDate', $startDate )
            ->setParameter( 'endDate', $endDate )
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Holiday[] Returns an array of Holiday objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Holiday
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
