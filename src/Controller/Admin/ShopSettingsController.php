<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OffDaysType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/salon', name: 'app_admin_shop_settings_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class ShopSettingsController extends AbstractController
{
    public function __construct( private EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/parametres', name: 'index' )]
    public function index() : Response
    {
        // get offdays
        $offdays = $this->entityManager->getRepository( Option::class )->findOneBy( ['name' => 'offdays'] );

        return $this->render( 'admin/shop_settings/index.html.twig', [
            'offdays' => $offdays,
        ] );
    }

    #[Route( '/jours-feries', name: 'offdays' )]
    public function offdays( Request $request )
    {
        $repository = $this->entityManager->getRepository( Option::class );
        $option = $repository->findOneBy( ['name' => 'offdays'] );
        if ( $option === null ) {
            $option = new Option( 'Jours de fermeture', 'offdays', '', ChoiceType::class );
            $this->entityManager->persist( $option );
            $this->entityManager->flush();
        }

        $offDaysArray = explode( ',', $option->getValue() );
        $formOffDays = $this->createForm( OffDaysType::class, ['offdays' => $offDaysArray] );


        $formOffDays->handleRequest( $request );

        if ( $formOffDays->isSubmitted() && $formOffDays->isValid() ) {
            $offdays = $formOffDays->get( 'offdays' )->getData();
            $offdays = implode( ',', $offdays );
            $option->setValue( $offdays );

            $this->entityManager->persist( $option );
            $this->entityManager->flush();

            $this->addFlash( 'success', 'Les jours de fermeture ont été mis à jour.' );

            return $this->redirectToRoute( 'app_admin_shop_settings_offdays' );
        }

        return $this->render( 'admin/shop_settings/offdays.html.twig', [
            'form' => $formOffDays->createView(),
        ] );
    }

}