<?php

namespace App\Infrastructure\Payment\Event;

use App\Domain\Payment\Entity\Payment;

class PaymentEvent
{
    public function __construct( private readonly  Payment $payment ){}

    public function getPayment(): Payment
    {
        return $this->payment;
    }

}