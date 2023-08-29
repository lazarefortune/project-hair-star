<?php

namespace App\Security\Voter;

use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermissionVoter extends Voter
{
    protected function supports( string $attribute, $subject ) : bool
    {
        // Vérifie si l'attribut est une instance de Permission
//        return $subject instanceof Permission;
        return true;
    }

    protected function voteOnAttribute( string $attribute, $subject, TokenInterface $token ) : bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté, il n'a pas les permissions nécessaires
        if ( !$user instanceof User ) {
            return false;
        }

        // Récupère les permissions de l'utilisateur à partir de son rôle
//        $userPermissionsCollection = $user->getRole()->getPermissions();
//
//        $userPermissions = [];
//        foreach ( $userPermissionsCollection as $userPermission ) {
//            $userPermissions[] = $userPermission->getName();
//        }
//
//        // Vérifie si l'utilisateur a la permission requise
//        return in_array( $attribute, $userPermissions );

        return true;
    }
}
