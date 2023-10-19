<?php

namespace App\Security;

use App\Entity\User;
use App\Service\AppConfigService;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly MailerInterface            $mailer,
        private readonly EntityManagerInterface     $entityManager,
        private readonly MailService                $mailService,
        private readonly AppConfigService           $appConfigService
    )
    {
    }

    public function getVerifyEmailSignature( string $verifyEmailRouteName, UserInterface $user ) : VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
    }

    public function sendEmailConfirmation( UserInterface $user )
    {
        $signature = $this->getVerifyEmailSignature(
            'app_verify_email',
            $user
        );

        $data = [
            'user' => $user,
            'signedUrl' => $signature->getSignedUrl(),
            'expiresAtMessageKey' => $signature->getExpirationMessageKey(),
            'expiresAtMessageData' => $signature->getExpirationMessageData()
        ];

        $email = $this->mailService->createEmail( 'mails/profile/confirm-email.twig', $data )
            ->to( $user->getEmail() )
            ->subject( 'Confirmation de votre adresse email' );

        $this->mailService->send( $email );
    }

    public function sendWelcomeEmailConfirmation( UserInterface $user )
    {
        $signature = $this->getVerifyEmailSignature(
            'app_verify_email',
            $user
        );

        $data = [
            'user' => $user,
            'signedUrl' => $signature->getSignedUrl(),
            'expiresAtMessageKey' => $signature->getExpirationMessageKey(),
            'expiresAtMessageData' => $signature->getExpirationMessageData()
        ];

        $appName = $this->appConfigService->getAppName();
        $mailObject = "Bienvenue sur $appName, " . $user->getFullname() . " !";

        $email = $this->mailService->createEmail( 'mails/auth/register.twig', $data )
            ->to( $user->getEmail() )
            ->subject( $mailObject );

        $this->mailService->send( $email );
    }

    public function sendEmailConfirmationWithTemplated( string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email ) : void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context( $context );

        // TODO: Utiliser plus tard le service MailService
        $this->mailer->send( $email );
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation( Request $request, UserInterface $user ) : void
    {
        $this->verifyEmailHelper->validateEmailConfirmation( $request->getUri(), $user->getId(), $user->getEmail() );

        $user->setIsVerified( true );

        $this->entityManager->persist( $user );
        $this->entityManager->flush();
    }
}
