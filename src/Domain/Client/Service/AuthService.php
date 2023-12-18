<?php

namespace App\Domain\Client\Service;

use App\Domain\Auth\Dto\SubscribeClientData;
use App\Domain\Auth\Event\UserCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthService
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly EventDispatcherInterface    $eventDispatcher,
        private readonly UserPasswordHasherInterface $userPasswordHasher )
    {
    }

    public function subscribeClient( SubscribeClientData $newClientDto ) : void
    {
        $client = $newClientDto->user->setEmail( $newClientDto->email )
            ->setFullname( $newClientDto->fullname )
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $newClientDto->user,
                    $newClientDto->plainPassword
                )
            )
            ->setRoles( ['ROLE_CLIENT'] )
            ->setCgu( true );

        $this->entityManager->persist( $client );
        $this->entityManager->flush();

        $registrationEvent = new UserCreatedEvent( $client );
        $this->eventDispatcher->dispatch( $registrationEvent, UserCreatedEvent::NAME );
    }
}