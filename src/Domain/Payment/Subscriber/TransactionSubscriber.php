<?php

namespace App\Domain\Payment\Subscriber;

use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\Entity\Transaction;
use App\Domain\Payment\Event\TransactionCompletedEvent;
use App\Domain\Payment\Event\TransactionStatusCheckEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TransactionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface   $em,
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            TransactionStatusCheckEvent::class => 'onTransactionStatusCheck',
            TransactionCompletedEvent::class => 'onTransactionCompleted',
        ];
    }

    public function onTransactionCompleted( TransactionCompletedEvent $event ) : void
    {
        $transactionId = $event->getTransactionId();
        // find the transaction by id and update the status to success
        $transaction = $this->em->getRepository( Transaction::class )->find( $transactionId );
        if ( $transaction ) {
            $transaction->setStatus( 'completed' );
            $this->em->persist( $transaction );
            $this->em->flush();
        }

        // get the transactionItem
        $transactionItemId = $transaction->getTransactionItemId();
        $transactionItemType = $transaction->getTransactionItemType();

        // get the transactionItem by id and update the status to success
        $transactionItem = $this->em->getRepository( $transactionItemType )->find( $transactionItemId );
        if ( $transactionItem ) {
            $transactionItem->setPaymentStatus( 'success' );
            $this->em->persist( $transactionItem );
            $this->em->flush();
        }
    }

    public function onTransactionStatusCheck( TransactionStatusCheckEvent $event ) : void
    {
        $transactionId = $event->getTransactionId();

        // Find transaction by id and check the status
        $transaction = $this->em->getRepository( Transaction::class )->find( $transactionId );
        if ( $transaction ) {
            // get all payments with status success and check the transaction status
            $payments = $this->em->getRepository( Payment::class )->findBy( [
                'transaction' => $transaction,
                'status' => 'success',
            ] );
            // Check total payments
            $totalPaid = 0;
            foreach ( $payments as $payment ) {
                $totalPaid += $payment->getAmount();
            }

            if ( $totalPaid == $transaction->getTotalAmount() ) {
                // dispatch an event to mark the transaction as completed
                $this->eventDispatcher->dispatch( new TransactionCompletedEvent( $transactionId ) );
            }
        }

        // if

    }
}