<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity( repositoryClass: RoleRepository::class )]
class Role
{

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 255 )]
    private ?string $name = null;

    #[ORM\ManyToMany( targetEntity: Permission::class, inversedBy: 'roles' )]
    private Collection $permissions;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $displayName = null;

    #[ORM\Column( type: Types::TEXT, nullable: true )]
    private ?string $description = null;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
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
     * @return Collection<int, Permission>
     */
    public function getPermissions() : Collection
    {
        return $this->permissions;
    }

    public function addPermission( Permission $permission ) : self
    {
        if ( !$this->permissions->contains( $permission ) ) {
            $this->permissions->add( $permission );
        }

        return $this;
    }

    public function removePermission( Permission $permission ) : self
    {
        $this->permissions->removeElement( $permission );

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
        return $this->getName();
    }
}
