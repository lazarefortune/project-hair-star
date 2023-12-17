<?php

namespace App\Domain\Auth;

use App\Domain\Auth\Entity\User;
use App\Infrastructure\AppConfigService;
use App\Infrastructure\Mailing\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

    public function getVerifyEmailSignature( string $verifyEmailRouteName, User $user ) : VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
    }

    public function sendEmailConfirmation( User $user ) : void
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

        $this->mailService->send( $email, UserEmailEnum::ACCOUNT_REQUEST_CONFIRMATION, $user );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendWelcomeEmailConfirmation( User $user ) : void
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

        $this->mailService->send( $email, UserEmailEnum::ACCOUNT_WELCOME, $user );
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
    public function handleEmailConfirmation( Request $request, User $user ) : void
    {
        $this->verifyEmailHelper->validateEmailConfirmation( $request->getUri(), $user->getId(), $user->getEmail() );

        $user->setIsVerified( true );

        $this->entityManager->persist( $user );
        $this->entityManager->flush();
    }
}
