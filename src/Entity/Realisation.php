<?php

namespace App\Entity;

use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealisationRepository::class)]
class Realisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column(nullable: true)]
    private ?int $tarif = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isTarifPublic = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateRealisation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'realisation', targetEntity: ImageRealisation::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureFin = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif / 100;
    }

    public function setTarif(?float $tarif): self
    {
        $tarif = (int) ($tarif * 100);
        $this->tarif = $tarif;

        return $this;
    }

    public function isIsTarifPublic(): ?bool
    {
        return $this->isTarifPublic;
    }

    public function setIsTarifPublic(?bool $isTarifPublic): self
    {
        $this->isTarifPublic = $isTarifPublic;

        return $this;
    }

    public function getDateRealisation(): ?\DateTime
    {
        return $this->dateRealisation;
    }

    public function setDateRealisation(?\DateTime $dateRealisation): self
    {
        $this->dateRealisation = $dateRealisation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, ImageRealisation>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageRealisation $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setRealisation($this);
        }

        return $this;
    }

    public function removeImage(ImageRealisation $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRealisation() === $this) {
                $image->setRealisation(null);
            }
        }

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(?\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(?\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }
}
