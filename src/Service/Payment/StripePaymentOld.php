<?php

namespace App\Service\Payment;

use App\Entity\Booking;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePaymentOld
{
    public function __construct(
        readonly private UrlGeneratorInterface  $urlGenerator,
        readonly private string                 $clientSecret,
        readonly private EntityManagerInterface $entityManager,
    )
    {
        Stripe::setApiKey( $this->clientSecret );
        Stripe::setApiVersion( '2020-08-27' );
    }


    public function startPayment( Booking $booking ) : ?string
    {
        $client = $booking->getClient();

        $url = $this->urlGenerator->generate( 'app_booking_payment_result', [], UrlGeneratorInterface::ABSOLUTE_URL );

        $amount = ( $booking->getAmount() ? : $booking->getPrestation()->getPrice() ) * 100;

        $session = Session::create( [
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Réservation n°' . $booking->getId(),
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $url . '?success=1',
            'cancel_url' => $url,
            'metadata' => [
                'booking_id' => $booking->getId(),
                'client_id' => $client->getId(),
            ],
        ] );

        $payment = new Payment();
        $payment->setBooking( $booking )
            ->setClient( $client )
            ->setAmount( $amount )
            ->setStatus( Payment::STATUS_PENDING )
            ->setSessionId( $session->id )
            ->setCreatedAt( new \DateTimeImmutable() )
            ->setUpdatedAt( new \DateTimeImmutable() );

        $booking->addPayment( $payment );

        $this->entityManager->persist( $booking );
        $this->entityManager->persist( $payment );
        $this->entityManager->flush();

        return $session->url;
    }

    public function pay()
    {
        // ...
    }
}