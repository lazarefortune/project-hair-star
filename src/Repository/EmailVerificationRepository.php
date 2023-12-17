<?php

namespace App\Repository;

use App\Domain\Auth\Entity\User;
use App\Entity\EmailVerification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailVerification>
 *
 * @method EmailVerification|null find( $id, $lockMode = null, $lockVersion = null )
 * @method EmailVerification|null findOneBy( array $criteria, array $orderBy = null )
 * @method EmailVerification[]    findAll()
 * @method EmailVerification[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class EmailVerificationRepository extends ServiceEntityRepository
{

    private const HOURS_TO_EXPIRE = 1;

    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, EmailVerification::class );
    }

    public function save( EmailVerification $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( EmailVerification $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatestEmailVerificationByUser( User $user )
    {
        return $this->createQueryBuilder( 'v' )
            ->where( 'v.author = :user' )
            ->setParameter( 'user', $user )
            ->setMaxResults( 1 )
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Delete expired email verifications and return the number of deleted rows
     * @return int
     */
    public function deleteExpiredEmailVerifications() : int
    {
        $date = new \DateTime();
        $date->modify( sprintf( '-%d hours', self::HOURS_TO_EXPIRE ) );

        return $this->createQueryBuilder( 'v' )
            ->delete()
            ->where( 'v.createdAt < :date' )
            ->setParameter( 'date', $date )
            ->getQuery()
            ->execute();

    }
}
