<?php

namespace App\Http\Controller;

use App\Domain\Password\Form\UpdatePasswordForm;
use App\Domain\Profile\Dto\ProfileUpdateData;
use App\Domain\Profile\Exception\TooManyEmailChangeException;
use App\Domain\Profile\Form\UserUpdateForm;
use App\Domain\Profile\Service\ProfileService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/mon-compte' )]
#[IsGranted( 'ROLE_USER' )]
class AccountController extends AbstractController
{
    public function __construct(
        private readonly ProfileService $profileService,
    )
    {
    }

    #[Route( '/', name: 'profile' )]
    #[isGranted( 'IS_AUTHENTICATED_FULLY' )]
    public function index( Request $request ) : Response
    {
        [$formProfile, $response] = $this->createFormProfile( $request );

        $user = $this->getUserOrThrow();

        if ( $response ) {
            return $response;
        }

        [$formPassword, $response] = $this->createFormPassword( $request );

        if ( $response ) {
            return $response;
        }

        // on vérifie si l'utilisateur a déjà demandé un changement d'email
        $requestEmailChange = $this->profileService->getLatestValidEmailVerification( $user );
        return $this->render( 'admin/profile/index.html.twig', [
            'formProfile' => $formProfile->createView(),
            'requestEmailChange' => $requestEmailChange,
            'formPassword' => $formPassword->createView(),
        ] );
    }

    private function createFormProfile( Request $request ) : array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm( UserUpdateForm::class, new ProfileUpdateData( $user ) );

        $form->handleRequest( $request );
        try {
            if ( $form->isSubmitted() && $form->isValid() ) {
                $data = $form->getData();
                $this->profileService->updateProfile( $data );

                if ( $data->email !== $user->getEmail() ) {
                    $this->addFlash( 'success', 'Vous allez recevoir un email pour confirmer votre nouvelle adresse email' );
                    return [$form, $this->redirectToRoute( 'app_profile' )];
                } else {
                    $this->addFlash( 'success', 'Informations mises à jour avec succès' );
                }

                return [$form, $this->redirectToRoute( 'app_profile' )];
            }
        } catch ( TooManyEmailChangeException ) {
            $this->addFlash( 'danger', 'Vous avez déjà demandé un changement d\'email, veuillez patienter avant de pouvoir en faire un nouveau' );
        }

        return [$form, null];
    }

    private function createFormPassword( Request $request ) : array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm( UpdatePasswordForm::class );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->profileService->updatePassword( $user, $form->get( 'password' )->getData() );

            $this->addFlash( 'success', 'Mot de passe mis à jour avec succès' );
            return [$form, $this->redirectToRoute( 'app_profile' )];
        }

        return [$form, null];
    }
}
