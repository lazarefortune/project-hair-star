<?php

namespace App\Service;

use App\Domain\Appointment\Repository\AppointmentRepository;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Event\UserCreatedEvent;
use App\Domain\Auth\Repository\UserRepository;
use App\Event\Client\DeleteClientEvent;
use App\Repository\EmailLogRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ClientService
{

    public function __construct(
        private readonly UserRepository           $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EmailLogRepository       $emailLogRepository,
        private readonly AppointmentRepository    $appointmentRepository,
    )
    {
    }

    /**
     * @return User[]
     */
    public function getClients() : array
    {
        return $this->userRepository->findByRole( 'ROLE_CLIENT' );
    }

    public function addNewClient( mixed $getData ) : void
    {
        $user = $this->userRepository->findOneBy( ['email' => $getData->getEmail()] );


        if ( $user ) {
            throw new \Exception( 'Un utilisateur avec cet email existe déjà' );
        }

        $user = $getData;
        $user->setRoles( ['ROLE_CLIENT'] );
        $user->setPassword( '' );
        $user->setCgu( false );
        $this->userRepository->save( $user, true );

        $registrationEvent = new UserCreatedEvent( $user );
        $this->eventDispatcher->dispatch( $registrationEvent, UserCreatedEvent::NAME );
    }

    /**
     * @throws \Exception
     */
    public function getClient( int $id ) : User
    {
        $user = $this->userRepository->findOneBy( ['id' => $id] );

        if ( !$user ) {
            throw new \Exception( 'Aucun client trouvé' );
        }

        return $user;
    }

    public function updateClient( User $client ) : void
    {
        $this->userRepository->save( $client, true );
    }

    public function deleteClient( User $client ) : void
    {
        $this->userRepository->remove( $client, true );
        $deleteClientEvent = new DeleteClientEvent( $client );
        $this->eventDispatcher->dispatch( $deleteClientEvent, DeleteClientEvent::NAME );
    }

    /**
     * Return an array of EmailLog objects sent to a client
     * @param User $client
     * @return array
     */
    public function getClientMailsLog( User $client, int $limit = null ) : array
    {
        return $this->emailLogRepository->getClientMailsLog( $client, $limit );
    }

    public function search( string $query )
    {
        return $this->userRepository->searchClientByNameAndEmail( $query );
    }

    public function getClientAppointments( User $client, int $limit = null )
    {
        return $this->appointmentRepository->createQueryBuilder( 'b' )
            ->where( 'b.client = :client' )
            ->setParameter( 'client', $client )
            ->orderBy( 'b.date', 'DESC' )
            ->addOrderBy( 'b.time', 'DESC' )
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult();
    }
}