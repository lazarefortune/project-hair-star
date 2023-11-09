<?php

namespace App\Payment\Stripe;

use App\Entity\Booking;
use App\Entity\User;
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
     * @param Booking $booking
     * @param string $url
     * @return string
     * @throws ApiErrorException
     */
    public function createBookingPaymentSession( Booking $booking, string $url ) : string
    {
        $session = $this->stripe->checkout->sessions->create( [
            'cancel_url' => $url,
            'success_url' => $url . '?success=1',
            'mode' => 'payment',
            'payment_method_types' => [
                'card',
            ],
            'customer' => $booking->getClient()->getStripeId(),
            'metadata' => [
                'booking_id' => $booking->getId(),
                'client_id' => $booking->getClient()->getId(),
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'booking_id' => $booking->getId(),
                    'client_id' => $booking->getClient()->getId(),
                ],
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Réservation n°' . $booking->getId(),
                        ],
                        'unit_amount' => $booking->getAmount() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
        ] );

        return $session->id;
    }

}