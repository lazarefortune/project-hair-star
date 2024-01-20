<?php

namespace App\Domain\PaymentTemp;

use App\Domain\PaymentTemp\Entity\Payment;

interface PaymentProcessorInterface
{
    public function supports( Payment $payment ) : bool;

    public function processPayment( Payment $payment ) : void;
}