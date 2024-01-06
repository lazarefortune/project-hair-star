<?php

namespace App\Http\Controller;

use App\Domain\Application\Entity\Option;
use App\Domain\Application\Form\WelcomeForm;
use App\Domain\Application\Model\WelcomeModel;
use App\Domain\Application\Service\OptionService;
use App\Domain\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route( '/', name: 'home' )]
    public function index() : Response
    {
        return $this->render( 'pages/index.html.twig', [
            'controller_name' => 'HomeController',
        ] );
    }

    #[Route( '/ui', name: 'ui' )]
    public function ui() : Response
    {
        return $this->render( 'pages/ui.html.twig' );
    }

    #[Route( '/bienvenue', name: 'welcome' )]
    public function welcome( Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, OptionService $optionService ) : Response
    {

        if ( $optionService->getValue( WelcomeModel::SITE_INSTALLED_NAME ) ) {
            return $this->redirectToRoute( 'app_home' );
        }

        $welcomeForm = $this->createForm( WelcomeForm::class, new WelcomeModel() );
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
            ) )
                ->setIsVerified( true )
                ->setCgu( true );

            $entityManager->persist( $siteTitle );
            $entityManager->persist( $siteInstalled );
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addFlash( 'success', 'Bienvenue sur votre nouveau site !' );
            return $this->redirectToRoute( 'app_success_installed' );
        }

        return $this->render( 'pages/welcome.html.twig', [
            'form' => $welcomeForm->createView(),
        ] );
    }

    #[Route( '/installe', name: 'success_installed' )]
    public function successInstalled( OptionService $optionService ) : Response
    {
        return $this->render( 'pages/success_installed.html.twig' );
    }

    #[Route( '/conditions-generales-utilisation', name: 'cgu' )]
    public function cgu( OptionService $optionService ) : Response
    {
        return $this->render( 'pages/cgu.html.twig' );
    }

    #[Route( '/mentions-legales', name: 'mentions_legales' )]
    public function mentionsLegales( OptionService $optionService ) : Response
    {
        return $this->render( 'pages/mentions_legales.html.twig' );
    }

    #[Route( '/politique-confidentialite', name: 'politique_confidentialite' )]
    public function politiqueConfidentialite( OptionService $optionService ) : Response
    {
        return $this->render( 'pages/politique_confidentialite.html.twig' );
    }


}
