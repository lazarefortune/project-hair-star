<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeWebhookController extends AbstractController
{
    private string $stripeWebhookSecret;

    public function __construct(
        readonly private PaymentRepository      $paymentRepository,
        readonly private EntityManagerInterface $entityManager,
        readonly private BookingRepository      $bookingRepository,
        ParameterBagInterface                   $parameterBag,
    )
    {
        $this->stripeWebhookSecret = $parameterBag->get( 'stripe_webhook_secret' );
    }

    #[Route( '/stripe/webhooks', name: 'stripe_webhook' )]
    public function handle( Request $request ) : Response
    {
        $payload = $request->getContent();
        $sig_header = $request->headers->get( 'Stripe-Signature' );

        $event = null;

        try {
            // Vérifiez la signature de la requête avec la clé secrète d'endpoint de votre webhook
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $this->stripeWebhookSecret
            );
        } catch ( \UnexpectedValueException $e ) {
            // Signature invalide
//            return new Response( 'Invalid request', 400 );
            return new Response(
                $e->getMessage() . $payload . ' ||signHed= ' . $sig_header . ' ||StripEnvKey= ' . $this->stripeWebhookSecret
                . ' ||Trace= ' . $e->getTraceAsString()
                ,
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']
            );
        } catch ( \Stripe\Exception\SignatureVerificationException $e ) {
            // Signature invalide
//            return new Response( 'Invalid request', 400 );
            return new Response(
                $e->getMessage() . $payload . ' ||signHed= ' . $sig_header . ' ||StripEnvKey= ' . $this->stripeWebhookSecret
                . ' ||Trace= ' . $e->getTraceAsString()
                ,
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']
            );
        }

        // Gérez l'événement
        switch ( $event->type ) {
            case 'checkout.session.completed':
                $data = $event->data['object'];
                $sessionId = $data->id;

                $bookingId = $data->metadata->booking_id;
                // find booking
                $booking = $this->bookingRepository->find( $bookingId );
                if ( $booking ) {
                    // update booking status
                    $booking->setPaymentStatus( Booking::PAYMENT_STATUS_SUCCESS );
                    // save booking
                    $this->entityManager->persist( $booking );
                    $this->entityManager->flush();
                }
                // Recherchez l'enregistrement de paiement correspondant dans votre base de données
                $payment = $this->paymentRepository->findOneBy( ['sessionId' => $sessionId] );

                if ( $payment ) {
                    // Mettez à jour le statut du paiement
                    $payment->setStatus( 'succeeded' );

                    // Enregistrez les modifications dans la base de données
                    $this->entityManager->persist( $payment );
                    $this->entityManager->flush();
                }
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object; // contains a StripePaymentIntent
                // Votre logique pour le paiement échoué
                break;
            // ... autres cas d'événements
        }

        return new Response( 'Received event', 200 );
    }
}

