<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Dto\Admin\Profile\AdminProfileUpdateDto;
use App\Exception\TooManyEmailChangeException;
use App\Form\Admin\UserProfileType;
use App\Form\UserChangePasswordType;
use App\Service\Admin\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/mon-compte', name: 'account_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class AdminUserController extends AbstractController
{
    public function __construct(
        private readonly ProfileService         $profileService,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route( '/', name: 'index' )]
    public function index( Request $request ) : Response
    {
        [$formProfile, $response] = $this->createFormProfile( $request );
        // on vérifie si l'utilisateur a déjà demandé un changement d'email
        $user = $this->getUserOrThrow();
        $requestEmailChange = $this->profileService->getRequestEmailChange( $user );

        if ( $response ) {
            return $response;
        }

        return $this->render( 'admin/account/index.html.twig', [
            'form' => $formProfile->createView(),
            'requestEmailChange' => $requestEmailChange,
        ] );
    }

    private function createFormProfile( Request $request ) : array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm( UserProfileType::class, new AdminProfileUpdateDto( $user ) );

        $form->handleRequest( $request );
        try {
            if ( $form->isSubmitted() && $form->isValid() ) {
                $data = $form->getData();
                $this->profileService->updateProfile( $data );
                $this->entityManager->flush();

                if ( $data->email !== $user->getEmail() ) {
                    $this->addToast( 'success', 'Informations mises à jour avec succès, un email de vérification vous a été envoyé à l\'adresse ' . $data->email );
                    return [$form, $this->redirectToRoute( 'app_admin_account_index' )];
                } else {
                    $this->addToast( 'success', 'Informations mises à jour avec succès' );
                }

                return [$form, $this->redirectToRoute( 'app_admin_account_index' )];
            }
        } catch ( TooManyEmailChangeException ) {
            $this->addToast( 'danger', 'Vous avez déjà demandé un changement d\'email, veuillez patienter 1h avant de pouvoir en faire un nouveau' );
        }

        return [$form, null];
    }

    #[Route( '/mot-de-passe', name: 'change_password' )]
    #[isGranted( 'IS_AUTHENTICATED_FULLY' )]
    public function password( Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher ) : Response
    {
        $formPassword = $this->createForm( UserChangePasswordType::class );
        $formPassword->handleRequest( $request );

        if ( $formPassword->isSubmitted() && $formPassword->isValid() ) {
            // TODO: Refactor this
            $currentPassword = $formPassword->get( 'currentPassword' )->getData();

            if ( !$passwordHasher->isPasswordValid( $this->getUser(), $currentPassword ) ) {
                $this->addToast( 'danger', 'Mot de passe actuel incorrect' );
                return $this->redirectToRoute( 'app_admin_account_change_password' );
            }

            $user = $this->getUser();
            // hash the plain password
            // TODO: move this to a service and add event listener to send mail
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $formPassword->get( 'password' )->getData()
                )
            );
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addToast( 'success', 'Mot de passe mis à jour avec succès' );
            return $this->redirectToRoute( 'app_admin_account_index' );
        }

        return $this->render( 'admin/account/change-password.html.twig', [
            'form' => $formPassword->createView(),
        ] );
    }
}
