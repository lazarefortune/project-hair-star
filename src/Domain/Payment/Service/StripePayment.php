<?php

namespace App\Domain\Payment\Service;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Auth\Entity\User;
use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\Stripe\StripeApi;
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

    public function payAppointment( Appointment $appointment, float $amount = 0 ) : ?string
    {
        if ( $amount > 0 ) {
            $this->handleAmountChange( $appointment, $amount );
        }

        $client = $this->ensureCustomerExists( $appointment );
        $payment = $this->ensurePaymentExists( $appointment, $client );

        return $this->processPaymentSession( $payment );
    }

    private function handleAmountChange( Appointment $appointment, float $amount ) : void
    {
        $appointment->setAmount( $amount );
        $this->deleteExistingSessions( $appointment );
//        $this->entityManager->flush();
    }

    private function deleteExistingSessions( Appointment $appointment ) : void
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

    private function ensureCustomerExists( Appointment $appointment ) : User
    {
        $client = $appointment->getClient();
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

    private function ensurePaymentExists( Appointment $appointment, User $client ) : Payment
    {
        $payment = $this->getExistingPendingPayment( $appointment, $client );
        if ( !$payment ) {
            return $this->createNewPayment( $appointment, $client );
        }

        $this->updatePaymentTimestamp( $payment );
        return $payment;
    }

    private function getExistingPendingPayment( Appointment $appointment, User $client ) : ?Payment
    {
        return $this->entityManager->getRepository( Payment::class )->findOneBy( [
            'appointment' => $appointment,
            'client' => $client,
            'status' => Payment::STATUS_PENDING,
        ] );
    }

    private function createNewPayment( Appointment $appointment, User $client ) : Payment
    {
        $url = $this->generateAppointmentPaymentStatusUrl( $appointment );
        $sessionId = $this->stripeApi->createAppointmentPaymentSession( $appointment, $url );

        $payment = new Payment();
        $payment->setSessionId( $sessionId )
            ->setAppointment( $appointment )
            ->setClient( $client )
            ->setAmount( $appointment->getAmount() )
            ->setStatus( Payment::STATUS_PENDING )
            ->setCreatedAt( new \DateTimeImmutable() )
            ->setUpdatedAt( new \DateTimeImmutable() );

        $this->entityManager->persist( $payment );
        $this->entityManager->flush();

        return $payment;
    }

    private function updateSessionForPayment( Payment $payment ) : void
    {
        $url = $this->generateAppointmentPaymentStatusUrl( $payment->getAppointment() );
        $sessionId = $this->stripeApi->createAppointmentPaymentSession( $payment->getAppointment(), $url );

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

    private function generateAppointmentPaymentStatusUrl( Appointment $appointment ) : string
    {
        return $this->urlGenerator->generate( 'app_appointment_payment_result', [
            'id' => $appointment->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL );
    }

    public function payAppointmentAcompte( Appointment $appointment )
    {
    }
}
