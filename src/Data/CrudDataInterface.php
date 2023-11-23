<?php

namespace App\Data;

interface CrudDataInterface
{
    public function getEntity() : object;

    public function getFormClass() : string;

    public function hydrate() : void;
}
