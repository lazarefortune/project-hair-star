<?php

namespace App\Domain\Payment;

use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PaymentService
{
    private array $processors = [];

    public function __construct(
        array                                   $processors,
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->processors = $processors;
    }

    /**
     * @throws Exception
     */
    public function pay( float $amount, TransactionItemInterface $transactionItem, string $paymentMethod ) : mixed
    {
        $payment = new Payment();
        $payment->setPaymentMethod( $paymentMethod );
        $payment->setAmount( $amount );

        return $this->processPayment( $payment, $transactionItem );
    }

    public function processPayment( Payment $payment, TransactionItemInterface $item ) : mixed
    {
        foreach ( $this->processors as $processor ) {
            if ( $processor->supports( $payment ) ) {

                // Gestion de la transaction
                $transaction = $this->entityManager->getRepository( Transaction::class )->findOneBy( [
                    'transactionItemId' => $item->getId(),
                    'transactionItemType' => $item::class
                ] );

                if ( !$transaction ) {
                    $transaction = new Transaction();
                }

                $transaction->setTotalAmount( $item->getAmount() );
                $transaction->setTransactionItem( $item );
                $transaction->setClient( $item->getClient() );

                $this->entityManager->persist( $transaction );
                $this->entityManager->flush();

                // check if the transaction has already been paid
                if ( $transaction->getStatus() === 'success' ) {
                    throw new Exception( "La transaction a déjà été complétée" );
                }

                // Gestion du paiement
                // On vérifie si la transaction a déjà été payée
                if ( $transaction->getStatus() === 'paid' ) {
                    throw new Exception( "La transaction a déjà été complétée" );
                }

                // On vérifie si le montant du paiement ne dépasse pas le montant de l'article
                if ( $payment->getAmount() > $item->getAmount() ) {
                    throw new Exception( "Le montant du paiement dépasse le montant de l'article" );
                }

                // On récupère tous les paiements VALIDES déjà effectués pour cette transaction
                $payments = $this->entityManager->getRepository( Payment::class )->findBy( [
                    'transaction' => $transaction,
                    'status' => 'success'
                ] );

                // On calcule le montant total déjà payé
                $totalPaid = 0;
                foreach ( $payments as $p ) {
                    $totalPaid += $p->getAmount();
                }

                $totalPaid = round( $totalPaid * 100 );
                $paymentAmount = round( $payment->getAmount() * 100 );
                $itemPrice = round( $item->getAmount() * 100 );

                if ( $totalPaid + $paymentAmount > $itemPrice ) {
                    throw new Exception( "Le montant du paiement est trop élevé" );
                }

                // On vérifie si il y a déjà un paiement en attente pour cette transaction
                $pendingPayment = $this->entityManager->getRepository( Payment::class )->findOneBy( [
                    'transaction' => $transaction,
                    'paymentMethod' => $payment->getPaymentMethod(),
                    'amount' => $payment->getAmount(),
                    'status' => 'pending'
                ] );

                if ( !$pendingPayment ) {
                    $payment->setTransaction( $transaction );
                    $this->entityManager->persist( $payment );
                    $this->entityManager->flush();
                } else {
                    $payment = $pendingPayment;
                }

                return $processor->processPayment( $payment, $item );
            }
        }
        throw new Exception( "Méthode de paiement non supportée" );
    }
}