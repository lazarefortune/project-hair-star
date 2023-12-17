<?php

namespace App\Data\Crud;

use App\Data\AutomaticCrudData;
use App\Domain\Auth\Entity\User;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property User $entity
 */
#[Unique( field: 'email' )]
class ClientCrudData extends AutomaticCrudData
{
    #[Assert\NotBlank]
    #[Assert\Length( min: 3 )]
    public string $fullname = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length( min: 10 )]
    public string $phone = '';

    public ?\DateTimeInterface $dateOfBirthday = null;

    public function hydrate() : void
    {
        parent::hydrate();
        $this->entity->setRoles( ['ROLE_CLIENT'] );
        $this->entity->setCgu( true );
        $this->entity->setPassword( "" );
        $this->entity->setUpdatedAt( new \DateTimeImmutable() );
        $this->entity->setDateOfBirthday( $this->dateOfBirthday );
    }
}