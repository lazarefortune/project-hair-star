<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: PermissionRepository::class )]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $name = null;

    #[ORM\ManyToMany( targetEntity: Role::class, mappedBy: 'permissions' )]
    private Collection $roles;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $displayName = null;

    #[ORM\Column( type: Types::TEXT, nullable: true )]
    private ?string $description = null;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * @return Collection<int, Role>
     */
    public function getRoles() : Collection
    {
        return $this->roles;
    }

    public function addRole( Role $role ) : self
    {
        if ( !$this->roles->contains( $role ) ) {
            $this->roles->add( $role );
            $role->addPermission( $this );
        }

        return $this;
    }

    public function removeRole( Role $role ) : self
    {
        if ( $this->roles->removeElement( $role ) ) {
            $role->removePermission( $this );
        }

        return $this;
    }

    public function getDisplayName() : ?string
    {
        return $this->displayName;
    }

    public function setDisplayName( ?string $displayName ) : self
    {
        $this->displayName = $displayName;

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

    public function __toString() : string
    {
        return $this->name;
    }
}
