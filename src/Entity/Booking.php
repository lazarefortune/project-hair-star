<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity( repositoryClass: BookingRepository::class )]
class Booking
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';

    public static array $statusList = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne( inversedBy: 'bookings' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?User $client = null;

    #[ORM\ManyToOne( inversedBy: 'bookings' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?Prestation $prestation = null;

    #[ORM\Column( type: Types::DATE_MUTABLE )]
    private ?\DateTimeInterface $bookingDate = null;

    #[ORM\Column( type: Types::TIME_MUTABLE )]
    private ?\DateTimeInterface $bookingTime = null;

    #[ORM\Column( type: Types::STRING, length: 50 )]
    private string $status = self::STATUS_PENDING;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getClient() : ?User
    {
        return $this->client;
    }

    public function setClient( ?User $client ) : self
    {
        $this->client = $client;

        return $this;
    }

    public function getPrestation() : ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation( ?Prestation $prestation ) : self
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getBookingDate() : ?\DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate( ?\DateTimeInterface $bookingDate ) : self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getBookingTime() : ?\DateTimeInterface
    {
        return $this->bookingTime;
    }

    public function setBookingTime( \DateTimeInterface $bookingTime ) : self
    {
        $this->bookingTime = $bookingTime;

        return $this;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function setStatus( string $status ) : self
    {
        if ( !in_array( $status, self::$statusList, true ) ) {
            throw new \InvalidArgumentException( 'Le statut n\'est pas valide.' );
        }

        $this->status = $status;

        return $this;
    }

    public function setIsConfirmed( bool $value ) : self
    {
        $this->setStatus( $value ? self::STATUS_CONFIRMED : self::STATUS_CANCELED );

        return $this;
    }
}
