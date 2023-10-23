<?php

namespace App\Service;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Repository\UserRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ClientService
{

    public function __construct(
        private readonly UserRepository           $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
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
            throw new \Exception( 'Aucun utilisateur trouvé' );
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
    }
}