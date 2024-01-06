<?php

namespace App\Http\Controller;

use App\Domain\Auth\Form\ForgotPasswordForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route( path: '/connexion', name: 'login' )]
    public function login( AuthenticationUtils $authenticationUtils ) : Response
    {
        if ( $this->getUser() ) {
            return $this->redirectToRoute( 'app_home' );
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render( 'auth/login.html.twig', ['last_username' => $lastUsername, 'error' => $error] );
    }

    #[Route( path: '/deconnexion', name: 'logout' )]
    public function logout() : void
    {
        throw new \LogicException( 'This method can be blank - it will be intercepted by the logout key on your firewall.' );
    }

    #[Route( path: '/mot-de-passe-oublie', name: 'forgot_password' )]
    public function forgotPassword( Request $request ) : Response
    {
        $form = $this->createForm( ForgotPasswordForm::class );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->addToast( 'success', 'Si votre adresse email est valide, vous allez recevoir un email vous permettant de réinitialiser votre mot de passe' );
            $this->redirectBack( 'app_forgot_password' );
        }

        return $this->render( 'auth/forgot-password.html.twig', [
            'form' => $form->createView(),
        ] );
    }
}
