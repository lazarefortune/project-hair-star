<?php

namespace App\Repository;

use App\Entity\CategoryPrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryPrestation>
 *
 * @method CategoryPrestation|null find( $id, $lockMode = null, $lockVersion = null )
 * @method CategoryPrestation|null findOneBy( array $criteria, array $orderBy = null )
 * @method CategoryPrestation[]    findAll()
 * @method CategoryPrestation[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class CategoryPrestationRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, CategoryPrestation::class );
    }

    public function save( CategoryPrestation $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( CategoryPrestation $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategoryService[] Returns an array of CategoryService objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryService
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
