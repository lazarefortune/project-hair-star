<?php

namespace App\Domain\Auth\Subscriber;

use App\Domain\Auth\EmailVerifier;
use App\Domain\Auth\Event\EmailConfirmSuccessEvent;
use App\Domain\Auth\Event\UserCreatedEvent;
use App\Domain\Auth\UserEmailEnum;
use App\Infrastructure\Mailing\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly EmailVerifier         $emailVerifier,
        private readonly MailService           $mailService,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated',
            EmailConfirmSuccessEvent::NAME => 'onEmailConfirmSuccess',
        ];
    }

    public function onUserCreated( UserCreatedEvent $event ) : void
    {
        $user = $event->getUser();

        $this->emailVerifier->sendWelcomeEmailConfirmation( $user );
    }

    public function onEmailConfirmSuccess( EmailConfirmSuccessEvent $event ) : void
    {
        $email = $this->mailService->createEmail( 'mails/profile/confirm-email-success.twig', [
            'user' => $event->getUser(),
            'login_url' => $this->urlGenerator->generate( 'app_login', [], UrlGeneratorInterface::ABSOLUTE_URL ),
            'contact_url' => $this->urlGenerator->generate( 'app_contact', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $event->getUser()->getEmail() )
            ->subject( 'Votre adresse email a été confirmée' );

        $this->mailService->send( $email, UserEmailEnum::ACCOUNT_CONFIRMATION_SUCCESS, $event->getUser() );
    }
}