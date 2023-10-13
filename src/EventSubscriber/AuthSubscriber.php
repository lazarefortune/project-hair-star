<?php

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use App\Security\EmailVerifier;
use App\Service\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class AuthSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly EmailVerifier $emailVerifier,
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated'
        ];
    }

    public function onUserCreated( UserCreatedEvent $event ) : void
    {
        $user = $event->getUser();

        $this->emailVerifier->sendWelcomeEmailConfirmation( $user );
    }
}