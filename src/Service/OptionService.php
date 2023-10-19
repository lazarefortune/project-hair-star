<?php

namespace App\Service;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class OptionService
{
    public function __construct(
        private OptionRepository                $optionRepository,
        private readonly CacheItemPoolInterface $cache
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

    /**
     * @throws InvalidArgumentException
     */
    public function getApplicationName() : string
    {
        $cacheItem = $this->cache->getItem( 'app_name' );

        if ( !$cacheItem->isHit() ) {
            $appName = $this->getValue( 'site_title' );
            $cacheItem->set( $appName );
            $cacheItem->expiresAfter( 86400 );  // Set cache TTL to 1 day
            $this->cache->save( $cacheItem );
        } else {
            $appName = $cacheItem->get();
        }

        return $appName;
    }
}