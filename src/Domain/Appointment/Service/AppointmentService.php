<?php

namespace App\Domain\Appointment\Service;

use App\Domain\Appointment\Dto\AppointmentData;
use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Appointment\Event\CanceledAppointmentEvent;
use App\Domain\Appointment\Event\ConfirmedAppointmentEvent;
use App\Domain\Appointment\Event\CreatedAppointmentEvent;
use App\Domain\Appointment\Event\UpdatedAppointmentEvent;
use App\Domain\Appointment\Repository\AppointmentRepository;
use App\Domain\Auth\Entity\User;
use App\Infrastructure\Security\TokenGeneratorService;
use Psr\EventDispatcher\EventDispatcherInterface;

class AppointmentService
{
    public function __construct(
        private readonly AppointmentRepository    $appointmentRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly TokenGeneratorService    $tokenGeneratorService
    )
    {
    }

    /**
     * @return Appointment[]
     */
    public function getAppointments() : array
    {
        return $this->appointmentRepository->findAllOrderedByDate();
    }

    public function addAppointment( AppointmentData $appointmentDto, bool $autoConfirm = false ) : void
    {
        $appointment = ( new Appointment() )->setClient( $appointmentDto->client )
            ->setPrestation( $appointmentDto->prestation )
            ->setDate( $appointmentDto->date )
            ->setTime( $appointmentDto->time )
            ->setAccessToken( $this->tokenGeneratorService->generate() )
            ->setSubTotal( $appointmentDto->prestation->getPrice() )
            ->setTotal( $appointmentDto->prestation->getPrice() );

        // TODO: refactor this
        if ( $autoConfirm ) {
            $appointment->setIsConfirmed( true );
        }

        $this->appointmentRepository->save( $appointment, true );

        $newAppointmentEvent = new CreatedAppointmentEvent( $appointment );

        $this->eventDispatcher->dispatch( $newAppointmentEvent );
    }

    public function updateAppointmentWithDto( AppointmentData $appointmentDto ) : void
    {
        $appointment = $appointmentDto->appointment->setClient( $appointmentDto->client )
            ->setPrestation( $appointmentDto->prestation )
            ->setDate( $appointmentDto->date )
            ->setTime( $appointmentDto->time );

        $this->appointmentRepository->save( $appointment, true );

        $this->eventDispatcher->dispatch( new UpdatedAppointmentEvent( $appointment ) );
    }

    public function deleteAppointment( Appointment $appointment ) : void
    {
        $this->appointmentRepository->remove( $appointment, true );
    }

    public function updateAppointment( Appointment $appointment ) : void
    {
        $this->appointmentRepository->save( $appointment, true );
    }

    public function confirmAppointment( Appointment $appointment ) : void
    {
        $appointment->setIsConfirmed( true );
        $this->appointmentRepository->save( $appointment, true );

        $this->eventDispatcher->dispatch( new ConfirmedAppointmentEvent( $appointment ) );
    }

    public function cancelAppointment( Appointment $appointment ) : void
    {
        $appointment->setIsConfirmed( false );
        $this->appointmentRepository->save( $appointment, true );

        $this->eventDispatcher->dispatch( new CanceledAppointmentEvent( $appointment ) );
    }

    /**
     * @return Appointment[]
     */
    public function getReservedAppointments() : array
    {
        return $this->appointmentRepository->findReservedAppointments();
    }

    public function getAppointmentByAccessToken( string $accessToken )
    {
        return $this->appointmentRepository->findOneBy( ['accessToken' => $accessToken] );
    }

    public function getAppointmentById( ?int $getId )
    {
        return $this->appointmentRepository->findOneBy( ['id' => $getId] );
    }

    public function getUserAppointments( User $user )
    {
        return $this->appointmentRepository->findBy( ['client' => $user] );
    }
}