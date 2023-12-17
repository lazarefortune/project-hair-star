<?php

namespace App\EventSubscriber;

use App\Domain\Auth\Event\EmailChangeVerificationEvent;
use App\Domain\Auth\UserEmailEnum;
use App\Infrastructure\Mailing\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;

class ProfileSubscriber implements EventSubscriberInterface
{

    public function __construct( private readonly MailService $mailService )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            EmailChangeVerificationEvent::class => 'onEmailChangeVerification',
        ];
    }

    public function onEmailChangeVerification( EmailChangeVerificationEvent $event ) : void
    {
        $user = $event->emailVerification->getAuthor();
        // On envoie un email de vérification
        $email = $this->mailService->createEmail( 'mails/profile/confirm-email-change.twig', [
            'token' => $event->emailVerification->getToken(),
            'username' => $user->getFullname(),
        ] )
            ->to( $event->emailVerification->getEmail() )
            ->subject( 'Vérification de votre nouvelle adresse email' )
            ->priority( Email::PRIORITY_HIGH );

        $this->mailService->send( $email, UserEmailEnum::ACCOUNT_UPDATED_EMAIL_REQUEST_CONFIRMATION, $user );
    }
}