<?php

namespace App\Domain\Profile\Service;

use App\Domain\Auth\Entity\User;
use App\Domain\Client\Service\AuthService;
use App\Domain\Profile\Event\UserDeleteRequestEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DeleteAccountService
{
    final public const DAYS_BEFORE_DELETION = 7;

    public function __construct(
        private readonly AuthService $authService,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function deleteUser( User $user , Request $request ) : void
    {
        if ($user->getDeletedAt() !== null) {
            throw new \LogicException('La suppression de ce compte est déjà programmée pour le ' . $user->getDeletedAt()->format('d/m/Y'));
        }

        $unavailableRolesForDeletion = ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];

        foreach ($unavailableRolesForDeletion as $role) {
            if (in_array($role, $user->getRoles())) {
                throw new \LogicException('Impossible de supprimer un compte administrateur');
            }
        }

        $this->authService->logout( $request );
        $this->dispatcher->dispatch( new UserDeleteRequestEvent( $user ) );
        $user->setDeletedAt( new \DateTimeImmutable( sprintf( '+%d days', self::DAYS_BEFORE_DELETION ) ) );
        $this->em->flush();
    }

    public function cancelAccountDeletion( User $user ) : void
    {
        $user->setDeletedAt( null );
        $this->em->flush();
    }
}