<?php

namespace App\Http\Controller;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Appointment\Repository\AppointmentRepository;
use App\Domain\Payment\Event\PaymentSuccessEvent;
use App\Domain\Payment\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class StripeWebhookController extends AbstractController
{
    private string $stripeWebhookSecret;

    public function __construct(
        readonly private PaymentRepository        $paymentRepository,
        readonly private EntityManagerInterface   $entityManager,
        readonly private AppointmentRepository    $appointmentRepository,
        readonly private EventDispatcherInterface $eventDispatcher,
        ParameterBagInterface                     $parameterBag,
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
            return new Response( 'Invalid request', 400 );
        } catch ( \Stripe\Exception\SignatureVerificationException $e ) {
            // Signature invalide
            return new Response( 'Invalid request', 400 );
        }

        // Gérez l'événement
        switch ( $event->type ) {
            case 'checkout.session.completed':
                $data = $event->data['object'];
                $sessionId = $data->id;

                $appointmentId = $data->metadata->appointment_id;
                // find appointment
                $appointment = $this->appointmentRepository->find( $appointmentId );
                if ( $appointment ) {
                    // update appointment status
                    $appointment->setPaymentStatus( Appointment::PAYMENT_STATUS_SUCCESS );
                    // save appointment
                    $this->entityManager->persist( $appointment );
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
                // TODO: dispatch event to send email to user with invoice
                $this->eventDispatcher->dispatch( new PaymentSuccessEvent( $appointmentId ) );
                break;

            case 'invoice.paid':
                $invoice = $event->data->object; // contains a StripeInvoice
                // Votre logique pour le paiement réussi
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

