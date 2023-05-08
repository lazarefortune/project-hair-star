<?php

namespace App\Data;

class PermissionData
{
    public static function getDefaultPermissions() : array
    {
        return [
            [
                'name' => 'can_manage_users',
                'displayName' => 'Can manage users',
                'description' => 'Can manage users'
            ],
            [
                'name' => 'can_manage_roles',
                'displayName' => 'Can manage roles',
                'description' => 'Can manage roles'
            ],
            [
                'name' => 'can_manage_bookings',
                'displayName' => 'Can manage bookings',
                'description' => 'Can manage bookings'
            ],
            [
                'name' => 'can_manage_realisations',
                'displayName' => 'Can manage realisations',
                'description' => 'Can manage realisations'
            ],
            [
                'name' => 'can_manage_clients',
                'displayName' => 'Can manage clients',
                'description' => 'Can manage clients'
            ],
        ];
    }
}
