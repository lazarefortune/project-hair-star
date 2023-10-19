<?php

namespace App\Dto;

use App\Entity\Contact;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDto
{
    #[Assert\NotBlank]
    #[Assert\Length( min: 2, max: 255 )]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length( min: 2, max: 255 )]
    public string $subject;

    #[Assert\NotBlank]
    #[Assert\Length( min: 10, max: 255 )]
    public string $message;

    public function __construct(
        private readonly Contact $contact,
    )
    {
    }
}