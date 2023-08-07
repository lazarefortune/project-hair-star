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
}