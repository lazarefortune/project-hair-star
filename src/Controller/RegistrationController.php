<?php

namespace App\Controller;

use App\Dto\Auth\SubscribeClientDto;
use App\Entity\User;
use App\Event\Auth\EmailConfirmSuccessEvent;
use App\Event\UserCreatedEvent;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use App\Service\Auth\AuthService;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    public function __construct(
        private readonly AuthService                $authService,
        private readonly UserAuthenticatorInterface $userAuthenticator,
        private readonly AppAuthenticator           $authenticator,
        private readonly EmailVerifier              $emailVerifier,
        private readonly EventDispatcherInterface   $eventDispatcher,
    )
    {
    }

    private function createSubsciptionForm( Request $request, User $user = new User() ) : array
    {

        $form = $this->createForm( RegistrationFormType::class, new SubscribeClientDto( $user ) );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->authService->subscribeClient( $data );

            return [$form, $this->userAuthenticator->authenticateUser(
                $user,
                $this->authenticator,
                $request
            )];
        }

        return [$form, null];
    }

    #[Route( '/inscription', name: 'app_register' )]
    public function register( Request $request ) : Response
    {

        [$form, $response] = $this->createSubsciptionForm( $request );

        if ( $response ) {
            return $response;
        }

        return $this->render( 'auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ] );
    }

    #[Route( '/email/envoie/re-verification', name: 'app_account_resend_verification_email' )]
    public function resendUserEmailVerification( Request $request ) : Response
    {

        $user = $this->getUser();

        if ( null === $user ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $this->emailVerifier->sendEmailConfirmation( $user );

        $this->addToast( 'success', 'Un email de confirmation vous a été envoyé.' );

        $previousPage = $request->headers->get( 'referer' );

        return $this->redirect( $previousPage );
    }

    #[Route( '/email/envoie/re-verification/{id}', name: 'app_account_resend_verification_email_to_user' )]
    public function resendToOneUserHisEmailVerification( Request $request, UserRepository $userRepository ) : Response
    {
        $userId = $request->get( 'id' );
        if ( null === $userId ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $user = $userRepository->find( $userId );
        if ( null === $user ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $this->emailVerifier->sendEmailConfirmation( $user );

        $this->addToast( 'success', 'Un email de confirmation vous a été envoyé.' );

        $previousPage = $request->headers->get( 'referer' );

        return $this->redirect( $previousPage );
    }

    #[Route( '/email/validation', name: 'app_verify_email' )]
    public function verifyUserEmail( Request $request, TranslatorInterface $translator, UserRepository $userRepository ) : Response
    {
        $userId = $request->get( 'id' );
        if ( null === $userId ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $user = $userRepository->find( $userId );
        if ( null === $user ) {
            return $this->redirectToRoute( 'app_register' );
        }

        if ( $user->isVerified() ) {
            return $this->render( 'auth/verify_email.html.twig', [
                'title' => 'Adresse email déjà vérifiée',
                'message' => 'Votre adresse email a déjà été vérifiée. Vous pouvez maintenant profiter pleinement des avantages de votre compte.'
            ] );
        }

        try {
            $this->emailVerifier->handleEmailConfirmation( $request, $user );

            $emailConfirmSuccessEvent = new EmailConfirmSuccessEvent( $user );
            $this->eventDispatcher->dispatch( $emailConfirmSuccessEvent, EmailConfirmSuccessEvent::NAME );

            return $this->render( 'auth/verify_email.html.twig', [
                'title' => 'Adresse email vérifiée',
                'message' => 'Merci d\'avoir vérifié votre adresse email. Vous pouvez maintenant profiter pleinement des avantages de votre compte.'
            ] );

        } catch ( VerifyEmailExceptionInterface $exception ) {
            $this->addToast( 'verify_email_error', $translator->trans( $exception->getReason(), [], 'VerifyEmailBundle' ) );

            return $this->redirectToRoute( 'app_register' );
        }

    }
}

