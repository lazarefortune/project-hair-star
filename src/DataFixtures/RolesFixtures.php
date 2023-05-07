<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RolesFixtures extends Fixture implements OrderedFixtureInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function load( ObjectManager $manager ) : void
    {
        $roles = [
            [
                'name' => self::ROLE_USER,
                'display_name' => 'User',
                'description' => 'User',
            ],
            [
                'name' => self::ROLE_ADMIN,
                'display_name' => 'Admin',
                'description' => 'Admin',
            ],
            [
                'name' => self::ROLE_SUPER_ADMIN,
                'display_name' => 'Super Admin',
                'description' => 'Super Admin',
            ]
        ];

        foreach ( $roles as $role ) {
            $entity = new Role();
            $entity->setName( $role['name'] );
            $entity->setDisplayName( $role['display_name'] );
            $entity->setDescription( $role['description'] );

            $manager->persist( $entity );
            $this->addReference( $role['name'], $entity );
        }

        $manager->flush();
    }

    public function getOrder() : int
    {
        return 1;
    }
}
