<?php

namespace App\EventSubscriber;

use App\Event\Booking\CanceledBookingEvent;
use App\Event\Booking\ConfirmedBookingEvent;
use App\Event\Booking\NewBookingEvent;
use App\Event\Booking\UpdateBookingEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingSubscriber implements EventSubscriberInterface
{

    public function __construct( private readonly MailService $mailService )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            NewBookingEvent::class => 'onNewBooking',
            UpdateBookingEvent::class => 'onUpdateBooking',
            ConfirmedBookingEvent::class => 'onConfirmedBooking',
            CanceledBookingEvent::class => 'onCanceledBooking',
        ];
    }

    public function onNewBooking( NewBookingEvent $event ) : void
    {
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/nouvelle-reservation-admin.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( $event->getBooking()->getClient()->getEmail() )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/nouvelle-reservation.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( 'admin@gmail.com' )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email );
    }

    public function onUpdateBooking( UpdateBookingEvent $event ) : void
    {
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/modification-reservation.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( $event->getBooking()->getClient()->getEmail() )
            ->subject( 'Votre réservation a été modifiée' );

        $this->mailService->send( $email );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/modification-reservation-admin.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( 'admin@gmail.com' )
            ->subject( 'Modification de la réservation' );

        $this->mailService->send( $email );
    }

    public function onConfirmedBooking( ConfirmedBookingEvent $event ) : void
    {
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/confirmation-reservation.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( $event->getBooking()->getClient()->getEmail() )
            ->subject( 'Votre réservation a été confirmée' );

        $this->mailService->send( $email );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/confirmation-reservation-admin.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( 'admin@gmail.com' )
            ->subject( 'Confirmation de la réservation' );

        $this->mailService->send( $email );
    }

    public function onCanceledBooking( CanceledBookingEvent $event ) : void
    {
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/annulation-reservation.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( $event->getBooking()->getClient()->getEmail() )
            ->subject( 'Votre réservation a été annulée' );

        $this->mailService->send( $email );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/annulation-reservation-admin.twig', [
            'booking' => $event->getBooking()
        ] )
            ->to( 'admin@gmail.com' )
            ->subject( 'Annulation de la réservation' );

        $this->mailService->send( $email );

    }
}