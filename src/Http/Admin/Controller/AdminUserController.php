<?php

namespace App\Http\Admin\Controller;

use App\Domain\Password\Form\UpdatePasswordForm;
use App\Domain\Profile\Dto\AdminProfileUpdateData;
use App\Domain\Profile\Exception\TooManyEmailChangeException;
use App\Domain\Profile\Form\UserProfileForm;
use App\Domain\Profile\Service\ProfileService;
use App\Http\Controller\AbstractController;
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
    #[isGranted( 'IS_AUTHENTICATED_FULLY' )]
    public function index( Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher ) : Response
    {
        [$formProfile, $response] = $this->createFormProfile( $request );
        // on vérifie si l'utilisateur a déjà demandé un changement d'email
        $user = $this->getUserOrThrow();
        $requestEmailChange = $this->profileService->getRequestEmailChange( $user );

        if ( $response ) {
            return $response;
        }

        $formPassword = $this->createForm( UpdatePasswordForm::class );
        $formPassword->handleRequest( $request );

        if ( $formPassword->isSubmitted() && $formPassword->isValid() ) {
            $user = $this->getUser();

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

        return $this->render( 'admin/account/index.html.twig', [
            'formProfile' => $formProfile->createView(),
            'requestEmailChange' => $requestEmailChange,
            'formPassword' => $formPassword->createView(),
        ] );
    }

    private function createFormProfile( Request $request ) : array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm( UserProfileForm::class, new AdminProfileUpdateData( $user ) );

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
}
