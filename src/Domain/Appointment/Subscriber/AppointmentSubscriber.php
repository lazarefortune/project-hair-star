<?php

namespace App\Domain\Appointment\Subscriber;

use App\Domain\Appointment\Event\CanceledAppointmentEvent;
use App\Domain\Appointment\Event\ConfirmedAppointmentEvent;
use App\Domain\Appointment\Event\NewAppointmentEvent;
use App\Domain\Appointment\Event\UpdateAppointmentEvent;
use App\Infrastructure\AppConfigService;
use App\Infrastructure\Mailing\Enum\AppointmentEmailEnum;
use App\Infrastructure\Mailing\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppointmentSubscriber implements EventSubscriberInterface
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
            NewAppointmentEvent::class => 'onNewAppointment',
            UpdateAppointmentEvent::class => 'onUpdateAppointment',
            ConfirmedAppointmentEvent::class => 'onConfirmAppointment',
            CanceledAppointmentEvent::class => 'onCanceledAppointment',
        ];
    }

    public function onNewAppointment( NewAppointmentEvent $event ) : void
    {

        $client = $event->getAppointment()->getClient();
        $appointment = $event->getAppointment();

        $appointmentUrlAdmin = $this->urlGenerator->generate( 'app_admin_appointment_show', ['id' => $event->getAppointment()->getId()], UrlGeneratorInterface::ABSOLUTE_URL );

        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/appointment/nouvelle-reservation.twig', [
            'appointment' => $appointment,
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email, AppointmentEmailEnum::APPOINTMENT_NEW, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/appointment/nouvelle-reservation-admin.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlAdmin,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Nouvelle réservation' );

        $this->mailService->send( $email );
    }

    public function onUpdateAppointment( UpdateAppointmentEvent $event ) : void
    {
        $client = $event->getAppointment()->getClient();
        $appointment = $event->getAppointment();

        $appointmentUrlAdmin = $this->urlGenerator->generate( 'app_admin_appointment_show', ['id' => $appointment->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        $appointmentUrlClient = $this->urlGenerator->generate( 'app_appointment_manage', ['token' => $appointment->getToken()], UrlGeneratorInterface::ABSOLUTE_URL );

        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/appointment/modification-reservation.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlClient,
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été modifiée' );

        $this->mailService->send( $email, AppointmentEmailEnum::APPOINTMENT_UPDATED, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/appointment/modification-reservation-admin.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlAdmin,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Modification d\'une réservation' );

        $this->mailService->send( $email );
    }

    public function onConfirmAppointment( ConfirmedAppointmentEvent $event ) : void
    {
        $client = $event->getAppointment()->getClient();
        $appointment = $event->getAppointment();

        $appointmentUrlAdmin = $this->urlGenerator->generate( 'app_admin_appointment_show', ['id' => $appointment->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        $appointmentUrlClient = $this->urlGenerator->generate( 'app_appointment_manage', ['token' => $appointment->getToken()], UrlGeneratorInterface::ABSOLUTE_URL );
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/appointment/confirmation-reservation.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlClient,
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été confirmée' );

        $this->mailService->send( $email, AppointmentEmailEnum::APPOINTMENT_CONFIRMATION, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/appointment/confirmation-reservation-admin.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlAdmin,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Confirmation d\'une réservation' );

        $this->mailService->send( $email );
    }

    public function onCanceledAppointment( CanceledAppointmentEvent $event ) : void
    {
        $client = $event->getAppointment()->getClient();
        $appointment = $event->getAppointment();

        $appointmentUrlAdmin = $this->urlGenerator->generate( 'app_admin_appointment_show', ['id' => $appointment->getId()], UrlGeneratorInterface::ABSOLUTE_URL );
        $appointmentUrlClient = $this->urlGenerator->generate( 'app_appointment_manage', ['token' => $appointment->getToken()], UrlGeneratorInterface::ABSOLUTE_URL );
        // Envoi de l'email de confirmation au client
        $email = $this->mailService->createEmail( 'mails/appointment/annulation-reservation.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlClient,
            'contact_url' => $this->urlGenerator->generate( 'app_contact', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre réservation a été annulée' );

        $this->mailService->send( $email, AppointmentEmailEnum::APPOINTMENT_CANCELLED, $client );

        // Envoi de l'email de notification à l'admin
        $email = $this->mailService->createEmail( 'mails/appointment/annulation-reservation-admin.twig', [
            'appointment' => $appointment,
            'appointment_url' => $appointmentUrlAdmin,
        ] )
            ->to( $this->appConfigService->getAdminEmail() )
            ->subject( 'Confirmation de l\'annulation de la réservation' );

        $this->mailService->send( $email );

    }
}