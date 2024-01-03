<?php

namespace App\Domain\Contact;

use App\Domain\Contact\Dto\ContactData;

class ContactService
{

    public function __construct()
    {
    }

    public function sendContactMessage( ContactData $contactDto ) : void
    {
        // TODO: Send email


        // TODO: Save contact message in database
    }

}