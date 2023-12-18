<?php

namespace App\Domain\Payment\Stripe;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Auth\Entity\User;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

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

    /**
     * Crée une session de paiement et renvoie l'URL de paiement.
     * @param User $user
     * @param Appointment $appointment
     * @param string $url
     * @return string
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

}