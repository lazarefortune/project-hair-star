<?php

namespace App\Domain\Payment\Entity;

use App\Domain\Auth\Entity\User;
use App\Domain\Payment\TransactionItemInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    private ?int $id = null;

    #[ORM\Column( type: Types::FLOAT )]
    private float $totalAmount;

    #[ORM\Column( type: Types::STRING, length: 255 )]
    private string $status = 'pending';

    #[ORM\ManyToOne( inversedBy: 'transactions' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?User $client = null;

    #[ORM\OneToMany( mappedBy: 'transaction', targetEntity: Payment::class, orphanRemoval: true )]
    private Collection $payments;

    #[ORM\Column( type: Types::INTEGER )]
    private int $transactionItemId;

    #[ORM\Column( type: Types::STRING, length: 255 )]
    private string $transactionItemType;

    #[ORM\Column( type: Types::DATETIME_IMMUTABLE )]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column( type: Types::DATETIME_MUTABLE )]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->payments = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getTotalAmount() : ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount( float $totalAmount ) : static
    {
        $this->totalAmount = $totalAmount;

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

    public function getClient() : ?User
    {
        return $this->client;
    }

    public function setClient( ?User $client ) : static
    {
        $this->client = $client;

        return $this;
    }

    public function getPayments() : Collection
    {
        return $this->payments;
    }

    public function addPayment( Payment $payment ) : static
    {
        if ( !$this->payments->contains( $payment ) ) {
            $this->payments[] = $payment;
            $payment->setTransaction( $this );
        }

        return $this;
    }

    public function removePayment( Payment $payment ) : static
    {
        if ( $this->payments->removeElement( $payment ) ) {
            // set the owning side to null (unless already changed)
            if ( $payment->getTransaction() === $this ) {
                $payment->setTransaction( null );
            }
        }

        return $this;
    }

    public function setTransactionItem( TransactionItemInterface $transactionItem ) : self
    {
        $this->transactionItemId = $transactionItem->getId();
        $this->transactionItemType = get_class( $transactionItem );

        return $this;
    }

    public function getTransactionItemType() : string
    {
        return $this->transactionItemType;
    }

    public function getTransactionItemId() : int
    {
        return $this->transactionItemId;
    }

    public function getCreatedAt() : \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt( \DateTimeImmutable $createdAt ) : static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt( \DateTimeInterface $updatedAt ) : static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}