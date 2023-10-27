<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: TagRepository::class )]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $name = null;

    #[ORM\ManyToMany( targetEntity: Prestation::class, mappedBy: 'tags' )]
    private Collection $prestations;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
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

    /**
     * @return Collection<int, Prestation>
     */
    public function getPrestations() : Collection
    {
        return $this->prestations;
    }

    public function addPrestation( Prestation $prestations ) : self
    {
        if ( !$this->prestations->contains( $prestations ) ) {
            $this->prestations->add( $prestations );
            $prestations->addTag( $this );
        }

        return $this;
    }

    public function removePrestation( Prestation $prestations ) : self
    {
        if ( $this->prestations->removeElement( $prestations ) ) {
            $prestations->removeTag( $this );
        }

        return $this;
    }
}
