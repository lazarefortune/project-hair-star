<?php

namespace App\Infrastructure\Payment\Cash;

use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\PaymentProcessorInterface;
use App\Domain\Payment\TransactionItemInterface;

class CashPaymentProcessor implements PaymentProcessorInterface
{
    public function supports( Payment $payment ) : bool
    {
        return $payment->getPaymentMethod() === 'cash';
    }

    public function processPayment( Payment $payment, TransactionItemInterface $transactionItem ) : mixed
    {
        // TODO: Implement processPayment() method.
        return null;
    }
}