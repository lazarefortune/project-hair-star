<?php

namespace App\Data;

class RoleData
{
    public static function getDefaultRoles() : array
    {
        return [
            [
                'name' => 'ROLE_ADMIN',
                'displayName' => 'Administrator',
                'description' => 'Administrator'
            ],
            [
                'name' => 'ROLE_USER',
                'displayName' => 'User',
                'description' => 'User'
            ],
            [
                'name' => 'ROLE_GUEST',
                'displayName' => 'Guest',
                'description' => 'Guest'
            ]
        ];
    }
}
