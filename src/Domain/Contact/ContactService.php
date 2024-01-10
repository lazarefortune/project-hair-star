<?php

namespace App\Domain\Contact;

use App\Domain\Contact\Dto\ContactData;
use App\Infrastructure\Mailing\MailService;

class ContactService
{

    public function __construct(
        private readonly MailService $mailService,
        private readonly string      $contactEmail
    )
    {
    }

    /**
     * Send contact message
     * @param ContactData $contactDto
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendContactMessage( ContactData $contactDto ) : void
    {
        if ( !$this->contactEmail ) {
            throw new \Exception( 'Invalid email' );
        }

        $contactEmail = $this->mailService->prepareEmail(
            $this->contactEmail,
            'Demande de contact: ' . $contactDto->subject,
            'mails/contact/contact-message.twig', [
            'name' => $contactDto->name,
            'message' => $contactDto->message,
        ] );

        $this->mailService->send( $contactEmail );
    }

}