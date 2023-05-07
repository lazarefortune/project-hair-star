<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FirstUserFixture extends Fixture implements DependentFixtureInterface
{

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct( UserPasswordHasherInterface $userPasswordHasher )
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load( ObjectManager $manager ) : void
    {
        $userAdmin = new User();
        $userAdmin->setEmail( 'admin@gmail.com' );
        $userAdmin->setPassword( $this->userPasswordHasher->hashPassword( $userAdmin, 'admin' ) );
        $userAdmin->setFullname( 'Admin' );
        $userAdmin->setIsVerified( true );
        $roleAdmin = $this->getReference( RolesFixtures::ROLE_ADMIN );
        $userAdmin->setRole( $roleAdmin );

        $manager->persist( $userAdmin );

        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
            RolesFixtures::class,
        ];
    }
}
