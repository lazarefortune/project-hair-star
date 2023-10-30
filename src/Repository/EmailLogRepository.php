<?php

namespace App\Repository;

use App\Entity\EmailLog;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailLog>
 *
 * @method EmailLog|null find( $id, $lockMode = null, $lockVersion = null )
 * @method EmailLog|null findOneBy( array $criteria, array $orderBy = null )
 * @method EmailLog[]    findAll()
 * @method EmailLog[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class EmailLogRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, EmailLog::class );
    }

    /**
     * Returns an array of EmailLog objects sent to a client
     * @param User $client
     * @return array
     */
    public function getClientMailsLog( User $client, $limit = null ) : array
    {
        $query = $this->createQueryBuilder( 'p' )
            ->select( 'p' )
            ->where( 'p.recipient = :client' )
            ->setParameter( 'client', $client )
            ->setMaxResults( $limit )
            ->orderBy( 'p.sentAt', 'DESC' )
            ->getQuery();

        return $query->getResult();
    }
    
}
