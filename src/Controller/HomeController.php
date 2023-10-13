<?php

namespace App\Controller;

use App\Entity\Option;
use App\Entity\User;
use App\Form\WelcomeType;
use App\Model\WelcomeModel;
use App\Service\MailService;
use App\Service\OptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route( '/', name: 'app_home' )]
    public function index() : Response
    {
        return $this->render( 'home/index.html.twig', [
            'controller_name' => 'HomeController',
        ] );
    }

    #[Route( '/bienvenue', name: 'app_welcome' )]
    public function welcome( Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, OptionService $optionService ) : Response
    {

        if ( $optionService->getValue( WelcomeModel::SITE_INSTALLED_NAME ) ) {
            return $this->redirectToRoute( 'app_home' );
        }

        $welcomeForm = $this->createForm( WelcomeType::class, new WelcomeModel() );
        $welcomeForm->handleRequest( $request );

        if ( $welcomeForm->isSubmitted() && $welcomeForm->isValid() ) {
            $data = $welcomeForm->getData();

            $siteTitle = new Option( WelcomeModel::SITE_TITLE_LABEL, WelcomeModel::SITE_TITLE_NAME, $data->getSiteTitle(), TextType::class );
            $siteInstalled = new Option( WelcomeModel::SITE_INSTALLED_LABEL, WelcomeModel::SITE_INSTALLED_NAME, true, CheckboxType::class );

            $user = new User();
            $user->setFullname( $data->getFullname() );
            $user->setEmail( $data->getUsername() );
            $user->setRoles( ['ROLE_SUPER_ADMIN'] );
            $user->setPassword( $passwordHasher->hashPassword(
                $user,
                $data->getPassword()
            ) );

            $entityManager->persist( $siteTitle );
            $entityManager->persist( $siteInstalled );
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addFlash( 'success', 'Bienvenue sur votre nouveau site !' );
            return $this->redirectToRoute( 'app_success_installed' );
        }

        return $this->render( 'home/welcome.html.twig', [
            'form' => $welcomeForm->createView(),
        ] );
    }

    #[Route( '/installe', name: 'app_success_installed' )]
    public function successInstalled( OptionService $optionService ) : Response
    {
        return $this->render( 'home/success_installed.html.twig' );
    }

    #[Route( '/conditions-generales-utilisation', name: 'app_cgu' )]
    public function cgu( OptionService $optionService ) : Response
    {
        return $this->render( 'home/cgu.html.twig' );
    }

    #[Route( '/mentions-legales', name: 'app_mentions_legales' )]
    public function mentionsLegales( OptionService $optionService ) : Response
    {
        return $this->render( 'home/mentions_legales.html.twig' );
    }

    #[Route( '/politique-confidentialite', name: 'app_politique_confidentialite' )]
    public function politiqueConfidentialite( OptionService $optionService ) : Response
    {
        return $this->render( 'home/politique_confidentialite.html.twig' );
    }


}
