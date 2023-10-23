<?php

namespace App\Service;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;

class PrestationService
{
    public function __construct( private readonly PrestationRepository $prestationRepository )
    {
    }


    public function save( Prestation $prestation, bool $flush = false ) : void
    {

        foreach ( $prestation->getTags() as $tag ) {
            if ( !$tag->getPrestations()->contains( $prestation ) ) {
                $tag->addPrestation( $prestation );
            }
        }

        if ( !$prestation->isConsiderChildrenForPrice() ) {
            $prestation->setChildrenAgeRange( null );
            $prestation->setChildrenPricePercentage( null );
        }

        $this->prestationRepository->save( $prestation, $flush );
    }

    /**
     * @return array<Prestation>
     */
    public function getAll() : array
    {
        return $this->prestationRepository->findAll();
    }

}