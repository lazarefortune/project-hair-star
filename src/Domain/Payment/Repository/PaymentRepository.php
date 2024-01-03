<?php

namespace App\Domain\Payment\Repository;

use App\Domain\Payment\Entity\Payment;
use App\Infrastructure\Orm\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Payment>
 */
class PaymentRepository extends AbstractRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, Payment::class );
    }
}
