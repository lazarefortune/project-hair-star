<?php

namespace App\Dto\Auth;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class SubscribeClientDto
{

    #[Assert\NotBlank( message: 'Veuillez renseigner votre nom complet' )]
    #[Assert\Length( min: 3, minMessage: 'Votre nom complet doit contenir au moins {{ limit }} caractères' )]
    public string $fullname = '';

    #[Assert\NotBlank( message: 'Veuillez renseigner votre adresse email' )]
    #[Assert\Email( message: 'Veuillez renseigner une adresse email valide' )]
    public string $email = '';

    #[Assert\NotBlank( message: 'Veuillez renseigner votre numéro de téléphone' )]
    #[Assert\Regex( pattern: '/^((\+)33|0)[1-9](\d{2}){4}$/', message: 'Veuillez renseigner un numéro de téléphone valide' )]
    public string $phone = '';

    #[Assert\NotBlank( message: 'Veuillez renseigner votre mot de passe' )]
    #[Assert\Length( min: 6, minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères' )]
    #[Assert\Regex( pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', message: 'Votre mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre' )]
    public string $plainPassword = '';

    public function __construct( public User $user )
    {
    }
}