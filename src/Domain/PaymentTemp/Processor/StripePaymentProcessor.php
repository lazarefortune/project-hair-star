<?php

namespace App\Domain\PaymentTemp\Processor;

use App\Domain\PaymentTemp\Entity\Payment;
use App\Domain\PaymentTemp\PaymentProcessorInterface;

class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function supports( Payment $payment ) : bool
    {
        return $payment->getPaymentMethod() === 'stripe';
    }

    public function processPayment( Payment $payment ) : void
    {
        // TODO: Implement processPayment() method.
    }

}