<?php

namespace App\Domain\PaymentTemp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column( type: Types::FLOAT )]
    private float $amount;

    #[ORM\Column( type: Types::STRING, length: 255 )]
    private string $status;

    #[ORM\Column( type: Types::STRING, length: 255 )]
    private string $paymentMethod;

    #[ORM\Column( type: Types::DATETIME_IMMUTABLE )]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column( type: Types::DATETIME_MUTABLE )]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

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

    public function getStatus() : ?string
    {
        return $this->status;
    }

    public function setStatus( string $status ) : static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentMethod() : ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod( string $paymentMethod ) : static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getCreatedAt() : ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt( \DateTimeImmutable $createdAt ) : static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt( \DateTimeInterface $updatedAt ) : static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}