<?php

namespace App\Infrastructure\Payment\Stripe;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Auth\Entity\User;
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
     */
    public function createCustomer( User $user ) : User
    {
        if ( $user->getStripeId() && !$this->isCustomerDeleted( $user->getStripeId() ) ) {
            return $user;
        }

        $client = $this->stripe->customers->create( [
            'metadata' => [
                'user_id' => (string)$user->getId(),
            ],
            'email' => $user->getEmail(),
            'name' => $user->getFullname(),
        ] );

        $user->setStripeId( $client->id );

        return $user;
    }

    public function isCustomerDeleted( string $customerId ) : bool
    {
        $customer = $this->stripe->customers->retrieve( $customerId );
        return $customer->isDeleted();
    }

    public function getCustomer( string $customerId ) : Customer
    {
        return $this->stripe->customers->retrieve( $customerId );
    }

    public function getPaymentIntent( string $id ) : PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve( $id );
    }

    public function getSession( string $id ) : Session
    {
        return $this->stripe->checkout->sessions->retrieve( $id );
    }

    public function getInvoice( string $invoice ) : Invoice
    {
        return $this->stripe->invoices->retrieve( $invoice );
    }

    public function getSubcription( string $subcription ) : Subscription
    {
        return $this->stripe->subscriptions->retrieve( $subcription );
    }

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
    public function createAppointmentPaymentSession( Appointment $appointment, string $url ) : string
    {
        $session = $this->stripe->checkout->sessions->create( [
            'cancel_url' => $url,
            'success_url' => $url . '?success=1',
            'mode' => 'payment',
            'payment_method_types' => [
                'card',
            ],
            'customer' => $appointment->getClient()->getStripeId(),
            'metadata' => [
                'appointment_id' => $appointment->getId(),
                'client_id' => $appointment->getClient()->getId(),
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'appointment_id' => $appointment->getId(),
                    'client_id' => $appointment->getClient()->getId(),
                ],
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Réservation n°' . $appointment->getId(),
                        ],
                        'unit_amount' => $appointment->getAmount() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
        ] );

        return $session->id;
    }

    public function getBillingUrl( User $user, string $returnUrl ) : string
    {
        $session = $this->stripe->billingPortal->sessions->create( [
            'customer' => $user->getStripeId(),
            'return_url' => $returnUrl,
        ] );

        return $session->url;
    }

}