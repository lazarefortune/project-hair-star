<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Event\AddUserEvent;
use App\Event\UserCreatedEvent;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    public function __construct( private EmailVerifier $emailVerifier, private EventDispatcherInterface $eventDispatcher )
    {
    }

    #[Route( '/inscription', name: 'app_register' )]
    public function register( Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager ) : Response
    {
        $user = new User();
        $form = $this->createForm( RegistrationFormType::class, $user );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get( 'plainPassword' )->getData()
                )
            );

            // Retrieve the default role from the database
            $defaultRole = $entityManager->getRepository( Role::class )->findOneBy( ['name' => 'ROLE_USER'] );

//            if ( !$defaultRole ) {
//                throw $this->createNotFoundException( 'The default role does not exist' );
//            }

            if ( $defaultRole ) {
                $user->setRole( $defaultRole );
            }

            $entityManager->persist( $user );
            $entityManager->flush();

            $registrationEvent = new UserCreatedEvent( $user );
            $this->eventDispatcher->dispatch( $registrationEvent, UserCreatedEvent::NAME );

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation( 'app_verify_email', $user,
                ( new TemplatedEmail() )
                    ->from( new Address( 'contact@my-space.fr', 'Jessy Hair' ) )
                    ->to( $user->getEmail() )
                    ->subject( 'Please Confirm your Email' )
                    ->htmlTemplate( 'auth/emails/confirmation_email.html.twig' )
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render( 'auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ] );
    }

    #[Route( '/resend/email', name: 'app_resend_email' )]
    public function resendUserEmailVerification( Request $request, TranslatorInterface $translator, UserRepository $userRepository ) : Response
    {
        $id = $request->get( 'id' );

        if ( null === $id ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $user = $userRepository->find( $id );

        if ( null === $user ) {
            return $this->redirectToRoute( 'app_register' );
        }

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation( 'app_verify_email', $user,
            ( new TemplatedEmail() )
                ->from( new Address( 'contact@my-space.fr', 'Jessy Hair' ) )
                ->to( $user->getEmail() )
                ->subject( 'Please Confirm your Email' )
                ->htmlTemplate( 'auth/emails/confirmation_email.html.twig' )
        );
        // do anything else you need here, like send an email

        $this->addFlash( 'success', 'Un email de confirmation vous a été envoyé.' );

        return $this->render( 'auth/verify_email.html.twig', [
            'title' => 'Email de confirmation envoyé',
            'message' => 'Un email de confirmation vous a été envoyé.'
        ] );
    }

    #[Route( '/verify/email', name: 'app_verify_email' )]
    public function verifyUserEmail( Request $request, TranslatorInterface $translator, UserRepository $userRepository ) : Response
    {
        $id = $request->get( 'id' );

        if ( null === $id ) {
            return $this->redirectToRoute( 'app_register' );
        }

        $user = $userRepository->find( $id );

        if ( null === $user ) {
            return $this->redirectToRoute( 'app_register' );
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation( $request, $user );
        } catch ( VerifyEmailExceptionInterface $exception ) {
            $this->addFlash( 'verify_email_error', $translator->trans( $exception->getReason(), [], 'VerifyEmailBundle' ) );

            return $this->redirectToRoute( 'app_register' );
        }

        return $this->render( 'auth/verify_email.html.twig', [
            'title' => 'Adresse email vérifiée',
            'message' => 'Merci d\'avoir vérifié votre adresse email. Vous pouvez maintenant profiter pleinement des avantages de votre compte.'
        ] );
    }
}

