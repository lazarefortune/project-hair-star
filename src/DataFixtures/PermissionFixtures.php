<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load( ObjectManager $manager ) : void
    {
        $permissions = [
            [
                'name' => 'can_manage_users',
                'display_name' => 'Can manage users',
                'description' => 'Can manage users',
            ],
            [
                'name' => 'can_manage_roles',
                'display_name' => 'Can manage roles',
                'description' => 'Can manage roles',
            ],
            [
                'name' => 'can_manage_permissions',
                'display_name' => 'Can manage permissions',
                'description' => 'Can manage permissions',
            ],
            [
                'name' => 'can_manage_settings',
                'display_name' => 'Can manage settings',
                'description' => 'Can manage settings',
            ]
        ];

        foreach ( $permissions as $permission ) {
            $entity = new Permission();
            $entity->setName( $permission['name'] );
            $entity->setDisplayName( $permission['display_name'] );
            $entity->setDescription( $permission['description'] );

            $manager->persist( $entity );
        }

        $manager->flush();
    }

    public function getOrder() : int
    {
        return 2;
    }
}
