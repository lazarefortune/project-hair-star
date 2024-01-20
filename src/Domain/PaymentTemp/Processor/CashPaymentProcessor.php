<?php

namespace App\Domain\PaymentTemp\Processor;

use App\Domain\PaymentTemp\Entity\Payment;
use App\Domain\PaymentTemp\PaymentProcessorInterface;

class CashPaymentProcessor implements PaymentProcessorInterface
{
    public function supports( Payment $payment ) : bool
    {
        return $payment->getPaymentMethod() === 'cash';
    }

    public function processPayment( Payment $payment ) : void
    {
        // TODO: Implement processPayment() method.
    }
}