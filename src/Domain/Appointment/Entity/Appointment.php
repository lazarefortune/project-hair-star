<?php

namespace App\Domain\Appointment\Entity;

use App\Domain\Appointment\Repository\AppointmentRepository;
use App\Domain\Auth\Entity\User;
use App\Domain\Payment\Entity\Payment;
use App\Domain\Payment\TransactionItemInterface;
use App\Domain\Prestation\Entity\Prestation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: AppointmentRepository::class )]
class Appointment implements TransactionItemInterface
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_END = 'end';

    public static array $statusList = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELED,
    ];

    public const PAYMENT_STATUS_SUCCESS = 'success';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_FAILED = 'failed';

    public function isStatusPending() : bool
    {
        return $this->getStatus() === self::STATUS_PENDING;
    }

    public function isStatusConfirmed() : bool
    {
        return $this->getStatus() === self::STATUS_CONFIRMED;
    }

    public function isStatusCanceled() : bool
    {
        return $this->getStatus() === self::STATUS_CANCELED;
    }

    public static array $paymentStatusList = [
        self::PAYMENT_STATUS_SUCCESS,
        self::PAYMENT_STATUS_PENDING,
        self::PAYMENT_STATUS_FAILED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne( inversedBy: 'appointments' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?User $client = null;

    #[ORM\ManyToOne( inversedBy: 'appointments' )]
    #[ORM\JoinColumn( nullable: false )]
    private ?Prestation $prestation = null;

    #[ORM\Column( type: Types::DATE_MUTABLE )]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column( type: Types::TIME_MUTABLE )]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column( type: Types::STRING, length: 50 )]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column( type: Types::FLOAT, nullable: true )]
    private ?float $amount = null;

    #[ORM\Column( type: Types::STRING, length: 50, nullable: true )]
    private ?string $paymentStatus = self::PAYMENT_STATUS_PENDING;

    #[ORM\OneToMany( mappedBy: 'appointment', targetEntity: Payment::class, orphanRemoval: true )]
    private Collection $payments;

    #[ORM\Column( type: Types::STRING, length: 50, nullable: true )]
    private ?string $token = null;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

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

    public function getDate() : ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate( ?\DateTimeInterface $date ) : self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime() : ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime( \DateTimeInterface $time ) : self
    {
        $this->time = $time;

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

    public function isPassed() : bool
    {

        $isAppointmentDatePassed = $this->getDate()->format( 'Y-m-d' ) < ( new \DateTime() )->format( 'Y-m-d' );

        if ( $isAppointmentDatePassed ) {
            return true;
        }

        if ( $this->getDate()->format( 'Y-m-d' ) === ( new \DateTime() )->format( 'Y-m-d' ) ) {
            return $this->getTime()->format( 'H:i' ) <= ( new \DateTime() )->format( 'H:i' );
        }

        return false;
    }

    public function getAmount() : ?float
    {
        return $this->amount;
    }

    public function setAmount( ?float $amount ) : static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentStatus() : ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus( ?string $paymentStatus ) : static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function isPaid() : bool
    {
        return $this->getPaymentStatus() === self::PAYMENT_STATUS_SUCCESS;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments() : Collection
    {
        return $this->payments;
    }

    public function addPayment( Payment $payment ) : static
    {
        if ( !$this->payments->contains( $payment ) ) {
            $this->payments->add( $payment );
            $payment->setAppointment( $this );
        }

        return $this;
    }

    public function removePayment( Payment $payment ) : static
    {
        if ( $this->payments->removeElement( $payment ) ) {
            // set the owning side to null (unless already changed)
            if ( $payment->getAppointment() === $this ) {
                $payment->setAppointment( null );
            }
        }

        return $this;
    }

    public function getToken() : ?string
    {
        return $this->token;
    }

    public function setToken( ?string $token ) : static
    {
        $this->token = $token;

        return $this;
    }

    public function getItemName() : ?string
    {
        return 'Rendez-vous n°' . $this->getId();
    }

    public function getItemDescription() : ?string
    {
        return $this->getPrestation()->getName();
    }
}
