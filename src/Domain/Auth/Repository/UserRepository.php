<?php

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Entity\User;
use App\Infrastructure\Orm\CleanableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find( $id, $lockMode = null, $lockVersion = null )
 * @method User|null findOneBy( array $criteria, array $orderBy = null )
 * @method User[]    findAll()
 * @method User[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, CleanableRepositoryInterface
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, User::class );
    }

    public function save( User $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( User $entity, bool $flush = false ) : void
    {
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword( PasswordAuthenticatedUserInterface $user, string $newHashedPassword ) : void
    {
        if ( !$user instanceof User ) {
            throw new UnsupportedUserException( sprintf( 'Instances of "%s" are not supported.', \get_class( $user ) ) );
        }

        $user->setPassword( $newHashedPassword );

        $this->save( $user, true );
    }

    /**
     * @return User[]
     */
    public function findByRole( string $string ) : array
    {
        return $this->createQueryBuilder( 'u' )
            ->andWhere( 'u.roles LIKE :role' )
            ->setParameter( 'role', '%' . $string . '%' )
            ->getQuery()
            ->getResult();
    }

    public function searchClientByNameAndEmail( string $query )
    {
        return $this->createQueryBuilder( 'u' )
            ->andWhere( 'u.roles LIKE :role' )
            ->andWhere( 'u.fullname LIKE :query OR u.email LIKE :query' )
            ->orderBy( 'u.createdAt', 'DESC' )
            ->setParameter( 'role', '%ROLE_CLIENT%' )
            ->setParameter( 'query', '%' . $query . '%' )
            ->getQuery()
            ->getResult();
    }

    public function removeAllUnverifiedAccount() : int
    {
        $date = new \DateTime();
        $date->modify( '-' . User::DAYS_BEFORE_DELETE_UNVERIFIED_USER . ' days' );

        return $this->createQueryBuilder( 'u' )
            ->delete()
            ->where( 'u.roles LIKE :role' )
            ->andWhere( 'u.createdAt < :date' )
            ->andWhere( 'u.isVerified = false' )
            ->setParameter( 'role', '%ROLE_CLIENT%' )
            ->setParameter( 'date', $date )
            ->getQuery()
            ->execute();
    }

    public function clean() : int
    {
        return $this->removeAllUnverifiedAccount();
    }
}
