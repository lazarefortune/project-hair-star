<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: PaymentRepository::class )]
class Payment
{

    public const STATUS_SUCCESS = 'success';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne( inversedBy: 'payments' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?Booking $booking = null;

    #[ORM\ManyToOne( inversedBy: 'payments' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?User $client = null;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $sessionId = null;

    #[ORM\Column( length: 50, nullable: true )]
    private ?string $status = null;

    #[ORM\Column( nullable: true )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column( type: Types::DATETIME_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getAmount() : ?float
    {
        return $this->amount;
    }

    public function setAmount( float $amount ) : static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBooking() : ?Booking
    {
        return $this->booking;
    }

    public function setBooking( ?Booking $booking ) : static
    {
        $this->booking = $booking;

        return $this;
    }

    public function getClient() : ?User
    {
        return $this->client;
    }

    public function setClient( ?User $client ) : static
    {
        $this->client = $client;

        return $this;
    }

    public function getSessionId() : ?string
    {
        return $this->sessionId;
    }

    public function setSessionId( ?string $sessionId ) : static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getStatus() : ?string
    {
        return $this->status;
    }

    public function setStatus( ?string $status ) : static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt() : ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt( ?\DateTimeImmutable $createdAt ) : static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt( ?\DateTimeInterface $updatedAt ) : static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
