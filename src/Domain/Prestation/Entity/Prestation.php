<?php

namespace App\Domain\Prestation\Entity;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Category\Entity\Category;
use App\Domain\Prestation\Repository\PrestationRepository;
use App\Domain\Tag\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: PrestationRepository::class )]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $name = null;

    #[ORM\Column( type: Types::TEXT, nullable: true )]
    private ?string $description = null;


    #[ORM\Column( type: "decimal", precision: 10, scale: 2, nullable: true )]
    private ?float $price = null;

    #[ORM\Column( type: Types::TIME_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column( type: Types::TIME_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column( type: Types::TIME_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $status = null;

    #[ORM\Column( type: Types::DATE_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column( type: Types::DATE_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column( nullable: true )]
    private ?int $avalaibleSpacePerPrestation = null;

    #[ORM\Column( type: Types::TIME_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $bufferTime = null;

    #[ORM\ManyToOne( inversedBy: 'prestations' )]
    private ?Category $categoryPrestation = null;

    #[ORM\ManyToMany( targetEntity: Tag::class, inversedBy: 'prestations' )]
    private Collection $tags;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column( nullable: true )]
    private ?bool $considerChildrenForPrice = null;

    #[ORM\Column( nullable: true )]
    private ?int $childrenAgeRange = null;

    #[ORM\Column( nullable: true )]
    private ?float $childrenPricePercentage = null;

    #[ORM\OneToMany( mappedBy: 'prestation', targetEntity: Appointment::class, orphanRemoval: true )]
    private Collection $bookings;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName( string $name ) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription( ?string $description ) : self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice() : ?float
    {
        return $this->price;
    }

    public function setPrice( ?float $price ) : self
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration() : ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration( ?\DateTimeInterface $duration ) : self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getStartTime() : ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime( ?\DateTimeInterface $startTime ) : self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime() : ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime( ?\DateTimeInterface $endTime ) : self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getStatus() : ?string
    {
        return $this->status;
    }

    public function setStatus( ?string $status ) : self
    {
        $this->status = $status;

        return $this;
    }

    public function getStartDate() : ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate( ?\DateTimeInterface $startDate ) : self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate() : ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate( ?\DateTimeInterface $endDate ) : self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAvalaibleSpacePerPrestation() : ?int
    {
        return $this->avalaibleSpacePerPrestation;
    }

    public function setAvalaibleSpacePerPrestation( ?int $avalaibleSpacePerPrestation ) : self
    {
        $this->avalaibleSpacePerPrestation = $avalaibleSpacePerPrestation;

        return $this;
    }

    public function getBufferTime() : ?\DateTimeInterface
    {
        return $this->bufferTime;
    }

    public function setBufferTime( ?\DateTimeInterface $bufferTime ) : self
    {
        $this->bufferTime = $bufferTime;

        return $this;
    }

    public function getCategoryPrestation() : ?Category
    {
        return $this->categoryPrestation;
    }

    public function setCategoryPrestation( ?Category $categoryPrestation ) : self
    {
        $this->categoryPrestation = $categoryPrestation;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags() : Collection
    {
        return $this->tags;
    }

    public function addTag( Tag $tag ) : self
    {
        if ( !$this->tags->contains( $tag ) ) {
            $this->tags->add( $tag );
        }

        return $this;
    }

    public function removeTag( Tag $tag ) : self
    {
        $this->tags->removeElement( $tag );

        return $this;
    }

    public function isIsActive() : ?bool
    {
        return $this->isActive;
    }

    public function setIsActive( bool $isActive ) : self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isConsiderChildrenForPrice() : ?bool
    {
        return $this->considerChildrenForPrice;
    }

    public function setConsiderChildrenForPrice( ?bool $considerChildrenForPrice ) : self
    {
        $this->considerChildrenForPrice = $considerChildrenForPrice;

        if ( $considerChildrenForPrice === false ) {
            $this->setChildrenAgeRange( null );
            $this->setChildrenPricePercentage( null );
        }

        return $this;
    }

    public function getChildrenAgeRange() : ?int
    {
        return $this->childrenAgeRange;
    }

    public function setChildrenAgeRange( ?int $childrenAgeRange ) : self
    {
        $this->childrenAgeRange = $childrenAgeRange;

        return $this;
    }

    public function getChildrenPricePercentage() : ?float
    {
        return $this->childrenPricePercentage;
    }

    public function setChildrenPricePercentage( ?float $childrenPricePercentage ) : self
    {
        $this->childrenPricePercentage = $childrenPricePercentage;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getBookings() : Collection
    {
        return $this->bookings;
    }

    public function addBooking( Appointment $booking ) : self
    {
        if ( !$this->bookings->contains( $booking ) ) {
            $this->bookings->add( $booking );
            $booking->setPrestation( $this );
        }

        return $this;
    }

    public function removeBooking( Appointment $booking ) : self
    {
        if ( $this->bookings->removeElement( $booking ) ) {
            // set the owning side to null (unless already changed)
            if ( $booking->getPrestation() === $this ) {
                $booking->setPrestation( null );
            }
        }

        return $this;
    }
}
