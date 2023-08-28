<?php

namespace App\Service;

use App\Repository\PrestationRepository;

class PrestationService
{
    public function __construct( private readonly PrestationRepository $prestationRepository )
    {
    }


    public function save( $prestation, $flush = false ) : void
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

}