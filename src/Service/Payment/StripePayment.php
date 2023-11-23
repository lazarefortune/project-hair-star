<?php

namespace App\Service\Payment;

use App\Entity\Booking;
use App\Entity\Payment;
use App\Entity\User;
use App\Payment\Stripe\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePayment
{
    public function __construct(
        private StripeApi              $stripeApi,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface  $urlGenerator,
    )
    {
    }

    public function payBooking( Booking $booking, float $amount = 0 ) : ?string
    {
        if ( $amount > 0 ) {
            $this->handleAmountChange( $booking, $amount );
        }

        $client = $this->ensureCustomerExists( $booking );
        $payment = $this->ensurePaymentExists( $booking, $client );

        return $this->processPaymentSession( $payment );
    }

    private function handleAmountChange( Booking $booking, float $amount ) : void
    {
        $booking->setAmount( $amount );
        $this->deleteExistingSessions( $booking );
//        $this->entityManager->flush();
    }

    private function deleteExistingSessions( Booking $booking ) : void
    {
        // Add logic to delete sessions here, if applicable.
    }

    private function processPaymentSession( Payment $payment ) : ?string
    {
        $session = $this->stripeApi->getSession( $payment->getSessionId() );

        if ( $this->isSessionInvalid( $session ) ) {
            $this->updateSessionForPayment( $payment );
            $session = $this->stripeApi->getSession( $payment->getSessionId() );
        }

        return $session->url;
    }

    private function isSessionInvalid( $session ) : bool
    {
        return 'paid' === $session->payment_status || $session->expires_at < time();
    }

    private function ensureCustomerExists( Booking $booking ) : User
    {
        $client = $booking->getClient();
        if ( !$client->getStripeId() || $this->isCustomerDeleted( $client->getStripeId() ) ) {
            $client = $this->stripeApi->createCustomer( $client );
            $this->storeCustomerStripeId( $client );
        }
        return $client;
    }

    private function isCustomerDeleted( string $clientStripeId ) : bool
    {
        $customerStripe = $this->stripeApi->getCustomer( $clientStripeId );
        return $customerStripe->isDeleted();
    }

    private function storeCustomerStripeId( User $client ) : void
    {
        $this->entityManager->persist( $client );
        $this->entityManager->flush();
    }

    private function ensurePaymentExists( Booking $booking, User $client ) : Payment
    {
        $payment = $this->getExistingPendingPayment( $booking, $client );
        if ( !$payment ) {
            return $this->createNewPayment( $booking, $client );
        }

        $this->updatePaymentTimestamp( $payment );
        return $payment;
    }

    private function getExistingPendingPayment( Booking $booking, User $client ) : ?Payment
    {
        return $this->entityManager->getRepository( Payment::class )->findOneBy( [
            'booking' => $booking,
            'client' => $client,
            'status' => Payment::STATUS_PENDING,
        ] );
    }

    private function createNewPayment( Booking $booking, User $client ) : Payment
    {
        $url = $this->generateBookingPaymentStatusUrl( $booking );
        $sessionId = $this->stripeApi->createBookingPaymentSession( $booking, $url );

        $payment = new Payment();
        $payment->setSessionId( $sessionId )
            ->setBooking( $booking )
            ->setClient( $client )
            ->setAmount( $booking->getAmount() )
            ->setStatus( Payment::STATUS_PENDING )
            ->setCreatedAt( new \DateTimeImmutable() )
            ->setUpdatedAt( new \DateTimeImmutable() );

        $this->entityManager->persist( $payment );
        $this->entityManager->flush();

        return $payment;
    }

    private function updateSessionForPayment( Payment $payment ) : void
    {
        $url = $this->generateBookingPaymentStatusUrl( $payment->getBooking() );
        $sessionId = $this->stripeApi->createBookingPaymentSession( $payment->getBooking(), $url );

        $payment->setSessionId( $sessionId )
            ->setUpdatedAt( new \DateTimeImmutable() );

        $this->entityManager->persist( $payment );
        $this->entityManager->flush();
    }

    private function updatePaymentTimestamp( Payment $payment ) : void
    {
        $payment->setUpdatedAt( new \DateTimeImmutable() );
        $this->entityManager->persist( $payment );
        $this->entityManager->flush();
    }

    private function generateBookingPaymentStatusUrl( Booking $booking ) : string
    {
        return $this->urlGenerator->generate( 'app_booking_payment_result', [
            'id' => $booking->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL );
    }
}
