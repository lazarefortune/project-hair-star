<?php

namespace App\Service;

use App\Entity\Option;
use App\Repository\OptionRepository;

class OptionService
{
    public function __construct(
        private OptionRepository $optionRepository
    )
    {
    }

    public function getAll() : array
    {
        return $this->optionRepository->findAll();
    }

    public function getValue( string $name ) : mixed
    {
        $option = $this->optionRepository->findOneBy( ['name' => $name] );

        if ( $option instanceof Option ) {
            return $option->getValue();
        }

        return null;
    }

    public function findAll() : array
    {
        return $this->optionRepository->findAllForTwig();
    }
}