<?php

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{

    public function __construct( private readonly MailService $mailService )
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
        // Envoi de l'email de confirmation
        $email = $this->mailService->createEmail( 'mails/auth/register.twig', [
            'user' => $event->getUser()
        ] )
            ->to( $event->getUser()->getEmail() )
            ->subject( 'Bienvenue sur My Space' );

        $this->mailService->send( $email );
    }
}