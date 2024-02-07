<?php

namespace App\Domain\Payment;

use App\Domain\Payment\Entity\Payment;

interface PaymentProcessorInterface
{
    /**
     * Check if the payment is supported by the processor
     * @param Payment $payment
     * @return bool
     */
    public function supports( Payment $payment ) : bool;

    /**
     * Process the payment
     * @param Payment $payment
     * @param TransactionItemInterface $transactionItem
     * @return void
     */
    public function processPayment( Payment $payment, TransactionItemInterface $transactionItem ) : mixed;
}