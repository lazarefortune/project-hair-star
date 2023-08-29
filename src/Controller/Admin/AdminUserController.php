<?php

namespace App\Controller\Admin;

use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/mon-compte', name: 'app_admin_account_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class AdminUserController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( Request $request, EntityManagerInterface $entityManager ) : Response
    {
        $user = $this->getUser();
        $formUser = $this->createForm( UserType::class, $user );
        $formUser->handleRequest( $request );

        if ( $formUser->isSubmitted() && $formUser->isValid() ) {
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addFlash( 'success', 'Informations mises à jour avec succès' );
            return $this->redirectToRoute( 'app_admin_account_index' );
        }

        return $this->render( 'admin/account/index.html.twig', [
            'form' => $formUser->createView(),
        ] );
    }

    #[Route( '/mot-de-passe', name: 'password' )]
    #[isGranted( 'IS_AUTHENTICATED_FULLY' )]
    public function password( Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher ) : Response
    {
        $formPassword = $this->createForm( UserPasswordType::class );
        $formPassword->handleRequest( $request );

        if ( $formPassword->isSubmitted() && $formPassword->isValid() ) {
            $user = $this->getUser();
            // hash the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $formPassword->get( 'password' )->getData()
                )
            );
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addFlash( 'success', 'Mot de passe mis à jour avec succès' );
            return $this->redirectToRoute( 'app_admin_account_password' );
        }

        return $this->render( 'admin/account/password.html.twig', [
            'form' => $formPassword->createView(),
        ] );
    }
}
