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

        $email = $this->mailService->prepareEmail(
            $user->getEmail(),
            'Bienvenue sur ' . $_ENV['APP_NAME'],
            'mails/auth/welcome.twig',
            $data
        );

        $this->mailService->send( $email );
    }

    public function sendVerificationEmail( User $user ) : void
    {
        $signatureComponents = $this->emailVerifier->generateSignature( $user );

        $data = [
            'user' => $user,
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData()
        ];

        $email = $this->mailService->prepareEmail(
            $user->getEmail(),
            'Confirmez votre adresse email',
            'mails/auth/confirm-email.twig',
            $data
        );

        $this->mailService->send( $email );
    }

    public function sendVerificationSuccessEmail( User $user ) : void
    {

        $data = [
            'user' => $user,
            'loginUrl' => $this->urlGenerator->generate( 'app_login', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ];

        $email = $this->mailService->prepareEmail(
            $user->getEmail(),
            'Votre adresse email a été confirmée',
            'mails/auth/confirm-success.twig',
            $data
        );

        $this->mailService->send( $email );
    }
}