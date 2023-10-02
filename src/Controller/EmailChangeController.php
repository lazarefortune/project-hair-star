<?php

namespace App\Controller;

use App\Entity\EmailVerification;
use App\Repository\UserRepository;
use App\Service\Admin\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailChangeController extends AbstractController
{
    public function __construct(
        private readonly ProfileService         $profileService,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository         $userRepository
    )
    {
    }

    #[Route( '/changer-mon-email/{token}', name: 'user_email_confirm' )]
    #[ParamConverter( 'emailVerification', options: ['mapping' => ['token' => 'token']] )]
    public function confirmEmail( EmailVerification $emailVerification = null ) : Response
    {
        if ( !$emailVerification ) {
            return $this->handleInvalidOrExpiredToken( 'Le lien de changement d\'email est invalide' );
        }

        if ( $emailVerification->isExpired() ) {
            return $this->handleInvalidOrExpiredToken( 'Le lien de changement d\'email a expiré' );
        }

        return $this->handleValidToken( $emailVerification );
    }

    private function handleInvalidOrExpiredToken( string $message ) : Response
    {
        $this->addFlash( 'danger', $message );
        return $this->redirectToRoute( 'app_home' );
    }


    private function handleValidToken( EmailVerification $emailVerification ) : Response
    {
        $user = $this->userRepository->findOneBy( ['email' => $emailVerification->getEmail()] );

        if ( $user ) {
            $this->addFlash( 'danger', 'Cet email est déjà utilisé' );
            return $this->redirectToRoute( 'app_login' );
        }

        $this->profileService->updateEmail( $emailVerification );
        $this->entityManager->flush();
        $this->addFlash( 'success', 'Votre email a été mis à jour avec succès' );

        return $this->redirectToRoute( 'app_home' );
    }
}
