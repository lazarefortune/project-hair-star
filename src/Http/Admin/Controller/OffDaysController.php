<?php

namespace App\Http\Admin\Controller;

use App\Domain\Application\Entity\Option;
use App\Domain\Application\Form\OffDaysForm;
use App\Http\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/jours-de-fermeture', name: 'offdays_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class OffDaysController extends AbstractController
{
    public function __construct( private readonly EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/', name: 'index' )]
    public function list( Request $request ) : Response
    {
        $repository = $this->entityManager->getRepository( Option::class );
        $option = $repository->findOneBy( ['name' => 'offdays'] );
        if ( null === $option ) {
            $option = new Option( 'Jours de fermeture', 'offdays', '', ChoiceType::class );
            $this->entityManager->persist( $option );
            $this->entityManager->flush();
        }

        $offDaysArray = explode( ',', $option->getValue() );
        $formOffDays = $this->createForm( OffDaysForm::class, ['offdays' => $offDaysArray] );


        $formOffDays->handleRequest( $request );

        if ( $formOffDays->isSubmitted() && $formOffDays->isValid() ) {
            $offdays = $formOffDays->get( 'offdays' )->getData();
            $offdays = implode( ',', $offdays );
            $option->setValue( $offdays );

            $this->entityManager->persist( $option );
            $this->entityManager->flush();

            $this->addToast( 'success', 'Les jours de fermeture ont été mis à jour.' );

            return $this->redirectToRoute( 'app_admin_offdays_index' );
        }

        return $this->render( 'admin/off-days/list.html.twig', [
            'form' => $formOffDays->createView(),
        ] );
    }

}