<?php

namespace App\Controller;

use App\Dto\ContactDto;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public function __construct(
        private readonly ContactService $contactService,
    )
    {
    }

    #[Route( '/contact', name: 'app_contact' )]
    public function index( Request $request ) : Response
    {
        [$form, $response] = $this->createContactForm( $request );

        if ( $response ) {
            return $response;
        }

        return $this->render( 'contact/index.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    private function createContactForm( Request $request ) : array
    {
        $form = $this->createForm( ContactType::class, new ContactDto( new Contact() ) );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->contactService->sendContactMessage( $data );
            $this->addFlash( 'success', 'Votre message a bien été envoyé' );

            return [$form, $this->redirectToRoute( 'app_contact' )];
        }

        return [$form, null];
    }
}
