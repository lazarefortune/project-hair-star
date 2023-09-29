<?php

namespace App\Controller;

use App\Entity\EmailVerification;
use App\Repository\UserRepository;
use App\Service\Admin\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailChangeController extends AbstractController
{
    #[Route( '/changer-mon-email/{token}', name: 'user_email_confirm' )]
    public function confirmEmail(
        EmailVerification      $emailVerification,
        ProfileService         $profileService,
        EntityManagerInterface $entityManager,
        UserRepository         $userRepository
    ) : Response
    {
        if ( $emailVerification->isExpired() ) {
            $this->addFlash( 'danger', 'Le lien de changement d\'email a expiré' );
        } else {
            $user = $userRepository->findOneBy( ['email' => $emailVerification->getEmail()] );

            if ( $user ) {
                $this->addFlash( 'danger', 'Cet email est déjà utilisé' );

                return $this->redirectToRoute( 'app_login' );
            }

            $profileService->updateEmail( $emailVerification );
            $entityManager->flush();
            $this->addFlash( 'success', 'Votre email a été mis à jour avec succès' );
        }

        return $this->redirectToRoute( 'app_home' );
    }
}