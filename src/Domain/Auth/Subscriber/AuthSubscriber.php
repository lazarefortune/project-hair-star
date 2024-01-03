<?php

namespace App\Domain\Auth\Subscriber;

use App\Domain\Auth\Event\EmailConfirmationRequestedEvent;
use App\Domain\Auth\Event\EmailConfirmationCompletedEvent;
use App\Domain\Auth\Event\UserRegistrationCompletedEvent;
use App\Domain\Auth\Service\AuthMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly AuthMailService $authMailService,
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            UserRegistrationCompletedEvent::NAME => 'onUserRegistered',
            EmailConfirmationRequestedEvent::NAME => 'onEmailConfirmationRequested',
            EmailConfirmationCompletedEvent::NAME => 'onEmailConfirmSuccess',
        ];
    }

    public function onUserRegistered( UserRegistrationCompletedEvent $event ) : void
    {
        $user = $event->getUser();

        $this->authMailService->sendWelcomeEmail( $user );

    }


    public function onEmailConfirmationRequested( EmailConfirmationRequestedEvent $event ) : void
    {
        $user = $event->getUser();

        $this->authMailService->sendEmailConfirmationRequest( $user );
    }

    public function onEmailConfirmSuccess( EmailConfirmationCompletedEvent $event ) : void
    {
        $user = $event->getUser();

        $this->authMailService->sendEmailConfirmationSuccess( $user );
    }
}