<?php

namespace App\Domain\Auth\Service;

use App\Domain\Auth\EmailVerifier;
use App\Domain\Auth\Entity\User;
use App\Infrastructure\Mailing\MailService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthMailService
{
    public function __construct(
        private readonly MailService           $mailService,
        private readonly EmailVerifier         $emailVerifier,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function sendWelcomeEmail( User $user ) : void
    {
        $signatureComponents = $this->emailVerifier->generateSignature( $user );

        $data = [
            'user' => $user,
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData()
        ];

        $email = $this->mailService->createEmail( 'mails/auth/welcome.twig', $data )
            ->to( $user->getEmail() )
            ->subject( 'Bienvenue sur ' . $_ENV['APP_NAME'] );

        $this->mailService->send( $email );
    }

    public function sendEmailConfirmationRequest( User $user )
    {
        $signatureComponents = $this->emailVerifier->generateSignature( $user );

        $data = [
            'user' => $user,
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData()
        ];

        $email = $this->mailService->createEmail( 'mails/auth/confirm-request.twig', $data )
            ->to( $user->getEmail() )
            ->subject( 'Confirmez votre adresse email' );

        $this->mailService->send( $email );
    }

    public function sendEmailConfirmationSuccess( User $user )
    {
        $email = $this->mailService->createEmail( 'mails/auth/confirm-success.twig', [
            'user' => $user,
            'loginUrl' => $this->urlGenerator->generate( 'app_login', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $user->getEmail() )
            ->subject( 'Votre adresse email a été confirmée' );

        $this->mailService->send( $email );
    }
}