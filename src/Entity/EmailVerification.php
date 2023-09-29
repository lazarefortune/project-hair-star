<?php

namespace App\Entity;

use App\Repository\EmailVerificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: EmailVerificationRepository::class )]
class EmailVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $email = null;

    #[ORM\ManyToOne( inversedBy: 'emailVerifications' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?User $author = null;

    #[ORM\Column( length: 255 )]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setEmail( string $email ) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getAuthor() : ?User
    {
        return $this->author;
    }

    public function setAuthor( ?User $author ) : self
    {
        $this->author = $author;

        return $this;
    }

    public function getToken() : ?string
    {
        return $this->token;
    }

    public function setToken( string $token ) : self
    {
        $this->token = $token;

        return $this;
    }

    public function getCreatedAt() : ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt( \DateTimeImmutable $createdAt ) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isExpired() : bool
    {
        return $this->createdAt < new \DateTime( '-1 hour' );
    }
}
