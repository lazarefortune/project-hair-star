<?php

namespace App\EventSubscriber;

use App\Enum\BookingEmailType;
use App\Event\Booking\CanceledBookingEvent;
use App\Event\Booking\ConfirmedBookingEvent;
use App\Event\Booking\NewBookingEvent;
use App\Event\Booking\UpdateBookingEvent;
use App\Service\AppConfigService;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BookingSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly MailService           $mailService,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly AppConfigService      $appConfigService,
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            NewBookingEvent::class => 'onNewBooking',
            UpdateBookingEvent::class => 'onUpdateBooking',
            ConfirmedBookingEvent::class => 'onConfirmBooking',
            CanceledBookingEvent::class => 'onCanceledBooking',
        ];
    }

    public function onNewBooking( NewBookingEvent $event ) : void
    {

        $client = $event->getBooking()->getClient();
        $booking = $event->getBooking();

        $bookingUrl = $this->urlGenerator->generate( 'app_admin_booking_show', ['id' => $event->getBooking()->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/nouvelle-reservation.twig', [
            'booking' => $booking
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email, BookingEmailType::BOOKING_NEW, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/nouvelle-reservation-admin.twig', [
            'booking' => $booking,
            'booking_url' => $bookingUrl,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email );
    }

    public function onUpdateBooking( UpdateBookingEvent $event ) : void
    {
        $client = $event->getBooking()->getClient();
        $booking = $event->getBooking();

        $bookingUrl = $this->urlGenerator->generate( 'app_admin_booking_show', ['id' => $booking->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/modification-reservation.twig', [
            'booking' => $booking
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été modifiée' );

        $this->mailService->send( $email, BookingEmailType::BOOKING_UPDATED, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/modification-reservation-admin.twig', [
            'booking' => $booking,
            'booking_url' => $bookingUrl,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Modification d\'une réservation' );

        $this->mailService->send( $email );
    }

    public function onConfirmBooking( ConfirmedBookingEvent $event ) : void
    {
        $client = $event->getBooking()->getClient();
        $booking = $event->getBooking();

        $bookingUrl = $this->urlGenerator->generate( 'app_admin_booking_show', ['id' => $booking->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/confirmation-reservation.twig', [
            'booking' => $booking,
            'booking_url' => $bookingUrl,
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été confirmée' );

        $this->mailService->send( $email, BookingEmailType::BOOKING_CONFIRMATION, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/confirmation-reservation-admin.twig', [
            'booking' => $booking,
            'booking_url' => $bookingUrl,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Confirmation d\'une réservation' );

        $this->mailService->send( $email );
    }

    public function onCanceledBooking( CanceledBookingEvent $event ) : void
    {
        $client = $event->getBooking()->getClient();
        $booking = $event->getBooking();
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/booking/annulation-reservation.twig', [
            'booking' => $booking,
            'contact_url' => $this->urlGenerator->generate( 'app_contact', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été annulée' );

        $this->mailService->send( $email, BookingEmailType::BOOKING_CANCELLED, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/booking/annulation-reservation-admin.twig', [
            'booking' => $booking,
            'booking_url' => $this->urlGenerator->generate( 'app_admin_booking_show', ['id' => $booking->getId()], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Confirmation de l\'annulation de la réservation' );

        $this->mailService->send( $email );

    }
}