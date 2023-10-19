<?php

namespace App\Service;

class AppConfigService
{

    public function __construct( private readonly string $appName )
    {
    }

    public function getAppName() : string
    {
        return $this->appName;
    }
}
