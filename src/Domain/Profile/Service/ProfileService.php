<?php

namespace App\Domain\Profile\Service;

use App\Domain\Auth\Entity\EmailVerification;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Event\EmailChangeVerificationEvent;
use App\Domain\Auth\Repository\EmailVerificationRepository;
use App\Domain\Profile\Dto\AdminProfileUpdateData;
use App\Domain\Profile\Exception\TooManyEmailChangeException;
use App\Infrastructure\Security\TokenGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProfileService
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly EventDispatcherInterface    $eventDispatcher,
        private readonly EmailVerificationRepository $emailVerificationRepository,
        private readonly TokenGeneratorService       $tokenGeneratorService
    )
    {
    }

    public function updateProfile( AdminProfileUpdateData $data ) : void
    {
        $data->user->setFullname( $data->fullname );
        $data->user->setPhone( $data->phone );
        $data->user->setDateOfBirthday( $data->dateOfBirthday );

        if ( $data->email !== $data->user->getEmail() ) {
            $latestEmailVerification = $this->emailVerificationRepository->findLatestEmailVerificationByUser( $data->user );
            if ( $latestEmailVerification && $latestEmailVerification->getCreatedAt() > new \DateTimeImmutable( '-1 hour' ) ) {
                throw new TooManyEmailChangeException( $latestEmailVerification );
            } else {
                if ( $latestEmailVerification ) {
                    $this->entityManager->remove( $latestEmailVerification );
                }
            }
            // stocker en BDD les emails de vérification
            $emailVerification = ( new EmailVerification() )
                ->setEmail( $data->email )
                ->setAuthor( $data->user )
                ->setCreatedAt( new \DateTimeImmutable() )
                ->setToken( $this->tokenGeneratorService->generate() );

            $this->entityManager->persist( $emailVerification );

            // Event de vérification de l'email
            $this->eventDispatcher->dispatch( new EmailChangeVerificationEvent( $emailVerification ) );
        }
    }

    public function updateEmail( EmailVerification $emailVerification ) : void
    {
        $emailVerification->getAuthor()->setEmail( $emailVerification->getEmail() );
        $this->entityManager->remove( $emailVerification );
    }

    public function getRequestEmailChange( User $getUserOrThrow )
    {
        return $this->emailVerificationRepository->findLatestEmailVerificationByUser( $getUserOrThrow );
    }
}