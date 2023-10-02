<?php

namespace App\EventSubscriber;

use App\Event\NewBookingEvent;
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
            NewBookingEvent::class => 'onNewBooking'
        ];
    }

    public function onNewBooking( NewBookingEvent $event ) : void
    {
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/confirmation-reservation.twig', [
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
}