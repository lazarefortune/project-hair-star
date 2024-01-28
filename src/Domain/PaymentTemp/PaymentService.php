<?php

namespace App\Domain\PaymentTemp;

use App\Domain\PaymentTemp\Entity\Payment;

class PaymentService
{
    private array $processors = [];

    public function __construct( array $processors )
    {
        $this->processors = $processors;
    }

    public function processPayment( Payment $payment ) : void
    {
        foreach ( $this->processors as $processor ) {
            if ( $processor->supports( $payment ) ) {
                $processor->processPayment( $payment );
                return;
            }
        }
        throw new \Exception( "Méthode de paiement non supportée" );
    }
}