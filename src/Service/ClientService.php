<?php
namespace App\Service;

use App\Repository\UserRepository;

class ClientService {

    private UserRepository $userRepository;

    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    public function getClients() : array
    {
        return $this->userRepository->findByRole( 'ROLE_USER' );
    }
}