<?php

namespace App\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions() : array
    {
        return [
            new TwigFunction( 'icon', $this->showIcon( ... ), ['is_safe' => ['html']] ),
            new TwigFunction( 'menu_active', $this->menuActive( ... ), ['is_safe' => ['html'], 'needs_context' => true] ),
        ];
    }

    public function getFilters() : array
    {
        return [
            new TwigFilter( 'duration_format', [$this, 'durationFormat'] ),
            new TwigFilter( 'price_format', [$this, 'priceFormat'] ),
            new TwigFilter( 'is_older_than_hours', [$this, 'isOlderThanHours'] ),
            new TwigFilter( 'json_decode', [$this, 'jsonDecode'] ),
            new TwigFilter( 'date_age', [$this, 'formatDateAge'] ),
            new TwigFilter( 'human_date', [$this, 'formatHumanDate'] ),
            new TwigFilter( 'hour_lisible', [$this, 'formatHourLisible'] ),
        ];
    }

    public function formatHourLisible( \DateTime $date ) : string
    {
        return $date->format( 'H\hi' );
    }

    public function formatHumanDate( \DateTime $date ) : string
    {
        return $date->format( 'j F Y' );
    }

    public function formatDateAge( \DateTime $date ) : string
    {
        $now = new \DateTime();
        $interval = $now->diff( $date );

        if ( $interval->days == 0 ) {
            return 'aujourd\'hui';
        } elseif ( $interval->days == 1 ) {
            return 'hier';
        } elseif ( $interval->days < 30 ) {
            return 'il y a ' . $interval->days . ' jours';
        } elseif ( $interval->days < 365 ) {
            $months = round( $interval->days / 30 );
            return 'il y a ' . $months . ' mois';
        } else {
            $years = round( $interval->days / 365 );
            return 'il y a ' . $years . ' ans';
        }
    }


    /**
     * @param string $json
     * @return array<string, mixed>
     */
    public function jsonDecode( string $json ) : array
    {
        return json_decode( $json, true );
    }

    public function isOlderThanHours( \DateTimeImmutable $dateTime, int $hours ) : bool
    {
        $now = new \DateTimeImmutable();
        $interval = $now->diff( $dateTime );

        $totalHours = $interval->h + ( $interval->days * 24 );

        return $totalHours >= $hours;
    }

    public function priceFormat( float $price ) : string
    {
        return number_format( $price, 2, ',', ' ' ) . ' €';
    }

    public function durationFormat( ?\DateTime $dateTime ) : string
    {
        if ( !$dateTime ) {
            return '';
        }

        $hours = $dateTime->format( 'H' );
        $minutes = $dateTime->format( 'i' );

        if ( $hours > 0 ) {
            return $hours . 'h' . $minutes;
        }

        return $minutes . ' min';
    }

    public function showIcon( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = '' ) : string
    {
//        return $this->showLucideIconSvg( $iconName, $iconSize, $additionalClass );
        return $this->showIconWithLucideIcons( $iconName, $iconSize, $additionalClass );
    }

    /**
     * Show an icon with lucid icons from public folder
     * @return void
     */
    public function showLucideIconSvg( string $iconName, ?string $iconSize = null, ?string $additionalClass = null ) : string
    {
        $width = '17';
        $height = '17';

        // Map icon sizes to width and height
        $lucidIconSizes = [
            'xs' => ['width' => '12', 'height' => '12'],
            '3sm' => ['width' => '16', 'height' => '16'],
            '2sm' => ['width' => '18', 'height' => '18'],
            'sm' => ['width' => '20', 'height' => '20'],
            'md' => ['width' => '24', 'height' => '24'],
            'lg' => ['width' => '32', 'height' => '32'],
            'xl' => ['width' => '48', 'height' => '48'],
        ];

        if ( $iconSize && array_key_exists( $iconSize, $lucidIconSizes ) ) {
            $width = $lucidIconSizes[$iconSize]['width'];
            $height = $lucidIconSizes[$iconSize]['height'];
        }

        return <<<HTML
            <div style="width: {$width}px; height: {$height}px; line-height: {$height}px;" class="{$additionalClass}">
                <img src="/icons/lucide/{$iconName}.svg" width="{$width}" height="{$height}"  alt="{$iconName}">
            </div>
        HTML;
    }

    private function showIconWithLucideIcons( string $iconName, ?string $iconSize = null, ?string $classNames = '' ) : string
    {
        $width = '17';
        $height = '17';

        // Map icon sizes to width and height
        $lucidIconSizes = [
            'xs' => ['width' => '12', 'height' => '12'],
            '3sm' => ['width' => '16', 'height' => '16'],
            '2sm' => ['width' => '18', 'height' => '18'],
            'sm' => ['width' => '20', 'height' => '20'],
            'md' => ['width' => '24', 'height' => '24'],
            'lg' => ['width' => '32', 'height' => '32'],
            'xl' => ['width' => '48', 'height' => '48'],
        ];

        if ( $iconSize && array_key_exists( $iconSize, $lucidIconSizes ) ) {
            $width = $lucidIconSizes[$iconSize]['width'];
            $height = $lucidIconSizes[$iconSize]['height'];
        }

        return <<<HTML
            <div style="width: {$width}px; height: {$height}px; line-height: {$height}px;" class="{$classNames}">
                <i data-lucide="{$iconName}"  width="{$width}" height="{$height}"></i>
            </div>
        HTML;
    }

    /**
     * Ajout une class active pour les éléments actifs du menu.
     * @param array<string, mixed> $context
     * @param string $route
     * @return string
     */
    public function menuActive( array $context, string $route ) : string
    {
        $active = '';
        $request = $context['app']->getRequest();
        $currentRoute = $request->get( '_route' );

        if ( str_starts_with( $currentRoute, $route ) ) {
            $active = 'active';
        }

        return $active;
    }

}