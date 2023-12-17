<?php

namespace App\Http\Controller;

use App\Controller\AbstractController;
use App\Domain\Contact\ContactService;
use App\Domain\Contact\Dto\ContactData;
use App\Domain\Contact\Entity\Contact;
use App\Domain\Contact\Form\ContactForm;
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

    #[Route( '/contact', name: 'contact' )]
    public function index( Request $request ) : Response
    {
        [$form, $response] = $this->createContactForm( $request );

        if ( $response ) {
            return $response;
        }

        return $this->render( 'pages/contact.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    private function createContactForm( Request $request ) : array
    {
        $form = $this->createForm( ContactForm::class, new ContactData( new Contact() ) );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->contactService->sendContactMessage( $data );
            $this->addToast( 'success', 'Votre message a bien été envoyé.' );

            return [$form, $this->redirectToRoute( 'app_contact' )];
        }

        return [$form, null];
    }
}
