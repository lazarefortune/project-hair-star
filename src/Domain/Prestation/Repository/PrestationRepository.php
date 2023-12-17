<?php

namespace App\Domain\Prestation\Repository;

use App\Domain\Prestation\Entity\Prestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prestation>
 *
 * @method Prestation|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Prestation|null findOneBy( array $criteria, array $orderBy = null )
 * @method Prestation[]    findAll()
 * @method Prestation[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class PrestationRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, Prestation::class );
    }

    public function save( Prestation $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( Prestation $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }
}
