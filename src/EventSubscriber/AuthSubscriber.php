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
        private readonly MailService   $mailService,
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

        $signature = $this->emailVerifier->getVerifyEmailSignature(
            'app_verify_email',
            $user
        );

        $data = [
            'user' => $user,
            'signedUrl' => $signature->getSignedUrl(),
            'expiresAtMessageKey' => $signature->getExpirationMessageKey(),
            'expiresAtMessageData' => $signature->getExpirationMessageData()
        ];

        // Envoi de l'email de confirmation
        $email = $this->mailService->createEmail( 'mails/auth/register.twig', $data )
            ->to( $event->getUser()->getEmail() )
            ->subject( 'Bienvenue sur Hair Star' );

        $this->mailService->send( $email );
    }
}