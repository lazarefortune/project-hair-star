<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity( repositoryClass: UserRepository::class )]
#[ORM\Table( name: '`user`' )]
#[Vich\Uploadable]
#[UniqueEntity( fields: ['email'], message: 'There is already an account with this email' )]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( length: 180, unique: true )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $fullname = null;

    #[ORM\Column( type: 'boolean' )]
    private $isVerified = false;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $phone = null;

    #[ORM\Column( type: Types::DATE_MUTABLE, nullable: true )]
    private ?\DateTimeInterface $date_of_birthday = null;

    #[Vich\UploadableField( mapping: 'avatar_images', fileNameProperty: 'avatar' )]
    private ?File $avatarFile = null;

    #[ORM\Column( length: 255, nullable: true )]
    private ?string $avatar = null;

    #[ORM\Column( type: 'datetime', nullable: true )]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt() : ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt() : ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\Column( type: 'datetime', nullable: true )]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn( nullable: true )]
    private ?Role $role = null;

    #[ORM\OneToMany( mappedBy: 'client', targetEntity: Booking::class, orphanRemoval: true )]
    private Collection $bookings;

    #[ORM\OneToMany( mappedBy: 'author', targetEntity: EmailVerification::class, orphanRemoval: true )]
    private Collection $emailVerifications;

    public function __construct()
    {
        $this->roles = [Role::ROLE_USER, Role::ROLE_CLIENT];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->bookings = new ArrayCollection();
        $this->emailVerifications = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setEmail( string $email ) : self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier() : string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles() : array
    {
        $roles = $this->role === null ? [Role::ROLE_USER] : [$this->role->getName()];

        if ( $this->role === null ) {
            return $roles;
        }

        $permissions = $this->role->getPermissions()->map( function ( $permission ) {
            return $permission->getName();
        } )->toArray();

        return array_merge( $roles, $permissions );
    }

    public function setRoles( array $roles ) : self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    public function setPassword( string $password ) : self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullname() : ?string
    {
        return $this->fullname;
    }

    public function setFullname( ?string $fullname ) : self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function isVerified() : bool
    {
        return $this->isVerified;
    }

    public function setIsVerified( bool $isVerified ) : self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPhone() : ?string
    {
        return $this->phone;
    }

    public function setPhone( ?string $phone ) : self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDateOfBirthday() : ?\DateTimeInterface
    {
        return $this->date_of_birthday;
    }

    public function setDateOfBirthday( ?\DateTimeInterface $date_of_birthday ) : self
    {
        $this->date_of_birthday = $date_of_birthday;

        return $this;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }

    public function setAvatar( ?string $avatar ) : self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatarFile() : ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile( ?File $avatarFile ) : self
    {
        $this->avatarFile = $avatarFile;

        // update updatedAt only if file is not null
        if ( $avatarFile ) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function __sleep() : array
    {
        // Exclure l'attribut avatarFile de la sérialisation
        return array_diff( array_keys( get_object_vars( $this ) ), ['avatarFile'] );
    }

    public function getRole() : ?Role
    {
        return $this->role;
    }

    public function setRole( ?Role $role ) : self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings() : Collection
    {
        return $this->bookings;
    }

    public function addBooking( Booking $booking ) : self
    {
        if ( !$this->bookings->contains( $booking ) ) {
            $this->bookings->add( $booking );
            $booking->setClient( $this );
        }

        return $this;
    }

    public function removeBooking( Booking $booking ) : self
    {
        if ( $this->bookings->removeElement( $booking ) ) {
            // set the owning side to null (unless already changed)
            if ( $booking->getClient() === $this ) {
                $booking->setClient( null );
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmailVerification>
     */
    public function getEmailVerifications() : Collection
    {
        return $this->emailVerifications;
    }

    public function addEmailVerification( EmailVerification $emailVerification ) : self
    {
        if ( !$this->emailVerifications->contains( $emailVerification ) ) {
            $this->emailVerifications->add( $emailVerification );
            $emailVerification->setAuthor( $this );
        }

        return $this;
    }

    public function removeEmailVerification( EmailVerification $emailVerification ) : self
    {
        if ( $this->emailVerifications->removeElement( $emailVerification ) ) {
            // set the owning side to null (unless already changed)
            if ( $emailVerification->getAuthor() === $this ) {
                $emailVerification->setAuthor( null );
            }
        }

        return $this;
    }

}
