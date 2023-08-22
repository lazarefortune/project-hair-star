<?php

namespace App\Entity;

use App\Repository\CategoryServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: CategoryServiceRepository::class )]
class CategoryService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column( type: Types::TEXT, nullable: true )]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'categoryService', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
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


    public function isIsActive() : ?bool
    {
        return $this->isActive;
    }

    public function setIsActive( bool $isActive ) : self
    {
        $this->isActive = $isActive;

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

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setCategoryService($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getCategoryService() === $this) {
                $service->setCategoryService(null);
            }
        }

        return $this;
    }
}
