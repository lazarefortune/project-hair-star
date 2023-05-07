<?php

namespace App\Service;

use App\Entity\Realisation;
use App\Repository\RealisationRepository;

class RealisationService {

    private RealisationRepository $realisationRepository;

    public function __construct( RealisationRepository $realisationRepository )
    {
        $this->realisationRepository = $realisationRepository;
    }

    public function getRealisations() : array
    {
        return $this->realisationRepository->findAll();
    }

    public function getRealisation( int $id ) : ?Realisation
    {
        return $this->realisationRepository->find( $id );
    }

    public function save( $realisation, bool $flush = false ) : void
    {
        $this->realisationRepository->save( $realisation, $flush );
    }

    public function remove( $realisation, bool $flush = false ) : void
    {
        $this->realisationRepository->remove( $realisation, $flush );
    }

}