<?php

namespace App\Service;

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

        $this->userRepository->save( $user );
    }
}