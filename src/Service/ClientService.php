<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class ClientService
{

    private UserRepository $userRepository;

    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    public function getClients() : array
    {
        return $this->userRepository->findByRole( 'ROLE_USER' );
    }

    public function addNewClient( mixed $getData ) : void
    {
        $user = $this->userRepository->findOneBy( ['email' => $getData->getEmail()] );


        if ( $user ) {
            throw new \Exception( 'Un utilisateur avec cet email existe déjà' );
        }

        $user = $getData;
        $user->setRoles( ['ROLE_USER'] );
        $user->setPassword( '' );

        $this->userRepository->save( $user, true );
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