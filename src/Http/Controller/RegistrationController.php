<?php

namespace App\Http\Controller;

use App\Domain\Auth\Dto\NewUserData;
use App\Domain\Auth\EmailVerifier;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Event\EmailConfirmationCompletedEvent;
use App\Domain\Auth\Event\EmailConfirmationRequestedEvent;
use App\Domain\Auth\Form\RegistrationForm;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Security\AppAuthenticator;
use App\Domain\Client\Service\AuthService;
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
    )
    {
    }

    #[Route( '/inscription', name: 'register' )]
    public function register( Request $request ) : Response
    {

        $form = $this->createForm( RegistrationForm::class, new NewUserData( new User() ) );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $user = $this->authService->registerNewUser( $form->getData() );

            return $this->authenticateUser( $user, $request );
        }

        return $this->render( 'auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ] );
    }

    private function authenticateUser( User $user, Request $request ) : Response
    {
        $authenticationResponse = $this->userAuthenticator->authenticateUser(
            $user,
            $this->authenticator,
            $request
        );

        if ( !$authenticationResponse instanceof Response ) {
            throw new \Exception( "L'authentification de l'utilisateur a échoué." );
        }

        return $authenticationResponse;
    }

    #[Route( '/email/verification', name: 'send_verification_email' )]
    public function sendEmailVerification( Request $request ) : Response
    {
        $user = $this->getUserOrThrow();
        try {
            $this->authService->sendAccountConfirmationEmail( $user );
            $this->addFlash( 'success', 'Un email de confirmation vous a été envoyé.' );
        } catch ( \Exception $e ) {
            $this->addFlash( 'error', 'Erreur lors de l\'envoi de l\'email de confirmation.' );
        }

        return $this->redirectBack( 'app_profile' );
    }

    #[Route( '/email/validation', name: 'verify_email' )]
    public function verifyUserEmail( Request $request, UserRepository $userRepository ) : Response
    {
        $userId = $request->get( 'id' );

        if ( !$userId || !( $user = $userRepository->find( $userId ) ) ) {
            return $this->redirectToRoute( 'app_register' );
        }

        if ( $user->isVerified() ) {
            return $this->render( 'auth/verify_email.html.twig', [
                'title' => 'Adresse email déjà vérifiée',
                'message' => 'Votre adresse email a déjà été vérifiée. Vous pouvez maintenant profiter pleinement des avantages de votre compte.'
            ] );
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        $this->authService->confirmAccount( $user, $request->getUri() );

        return $this->render( 'auth/verify_email.html.twig', [
            'title' => 'Adresse email vérifiée',
            'message' => 'Merci d\'avoir vérifié votre adresse email. Vous pouvez maintenant profiter pleinement des avantages de votre compte.'
        ] );

    }
}

