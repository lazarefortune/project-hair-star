<?php

namespace App\Service;

use App\Dto\ContactDto;

class ContactService
{

    public function __construct()
    {
    }

    public function sendContactMessage( ContactDto $contactDto ) : void
    {
        // TODO: Send email


        // TODO: Save contact message in database
    }

}