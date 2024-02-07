<?php

namespace App\Infrastructure\Payment\Stripe;

use App\Domain\Auth\Entity\User;
use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\TransactionItemInterface;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Invoice;
use Stripe\PaymentIntent;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;

class StripeApi
{
    private StripeClient $stripe;

    public function __construct(
        string $privateKey,
    )
    {
        Stripe::setApiVersion( '2020-08-27' );
        $this->stripe = new StripeClient( $privateKey );
    }

    /**
     * Crée un customer stripe et sauvegarde l'id dans l'utilisateur.
     * @param array $customerData
     * @return string Client Stripe ID
     * @throws ApiErrorException
     */
    public function createCustomer( array $customerData ) : string
    {
        $client = $this->stripe->customers->create( [
            'metadata' => [
                'user_id' => (string)$customerData['userId'],
            ],
            'email' => $customerData['email'],
            'name' => $customerData['name'],
        ] );

        return $client->id;
    }

    /**
     * @throws ApiErrorException
     */
    public function isCustomerDeleted( string $customerId ) : bool
    {
        $customer = $this->stripe->customers->retrieve( $customerId );
        return $customer->isDeleted();
    }

    /**
     * @throws ApiErrorException
     */
    public function getCustomer( string $customerId ) : Customer
    {
        return $this->stripe->customers->retrieve( $customerId );
    }

    /**
     * @throws ApiErrorException
     */
    public function getPaymentIntent( string $id ) : PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve( $id );
    }

    /**
     * @throws ApiErrorException
     */
    public function getSession( string $id ) : Session
    {
        return $this->stripe->checkout->sessions->retrieve( $id );
    }

    /**
     * @throws ApiErrorException
     */
    public function getInvoice( string $invoice ) : Invoice
    {
        return $this->stripe->invoices->retrieve( $invoice );
    }

    /**
     * @throws ApiErrorException
     */
    public function getSubscription( string $subscription ) : Subscription
    {
        return $this->stripe->subscriptions->retrieve( $subscription );
    }

    /**
     * @throws ApiErrorException
     */
    public function getPlan( string $plan ) : Plan
    {
        return $this->stripe->plans->retrieve( $plan );
    }

    /**
     * Creates a subscription session and returns the payment URL.
     */
    public function createSubscriptionSession( User $user, string $url ) : string
    {
        // Implement this method to create a subscription session. Need to create Plan entity first.
        return $url;
    }

    /**
     * Crée une session de paiement et renvoie l'URL de paiement.
     * @throws ApiErrorException
     */
    public function createPaymentSession( Payment $payment, TransactionItemInterface $transactionItem, string $url ) : string
    {
        $session = $this->stripe->checkout->sessions->create( [
            'cancel_url' => $url,
            'success_url' => $url . '?success=1',
            'mode' => 'payment',
            'payment_method_types' => [
                'card',
            ],
            'customer' => $payment->getTransaction()->getClient()->getStripeId(),
            'metadata' => [
                'payment_id' => $payment->getId(),
                'client_id' => $payment->getTransaction()->getClient()->getId(),
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'payment_id' => $payment->getId(),
                    'client_id' => $payment->getTransaction()->getClient()->getId(),
                ],
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $transactionItem->getItemName(),
                        ],
                        'unit_amount' => $payment->getAmount() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
        ] );

        return $session->id;
    }

    /**
     * @throws ApiErrorException
     */
    public function getBillingUrl( User $user, string $returnUrl ) : string
    {
        $session = $this->stripe->billingPortal->sessions->create( [
            'customer' => $user->getStripeId(),
            'return_url' => $returnUrl,
        ] );

        return $session->url;
    }

}