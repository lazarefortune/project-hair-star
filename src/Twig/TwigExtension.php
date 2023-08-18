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
        ];
    }

    public function priceFormat( float $price ) : string
    {
        return number_format( $price, 2, ',', ' ' ) . ' €';
    }

    public function durationFormat( \DateTime $dateTime ) : string
    {
        $hours = $dateTime->format( 'H' );
        $minutes = $dateTime->format( 'i' );

        if ( $hours > 0 ) {
            return $hours . ' h ' . $minutes;
        }

        return $minutes . ' min';
    }

    public function showIcon( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = '' ) : string
    {
        return $this->showIconWithBoxIcons( $iconName, $iconSize, $additionalClass, $iconType );
    }

    private function showIconWithLineAwesome( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = 's' ) : string
    {
        $classNames = '';

        $lineAwesomeIconsSizes = ['xs', 'sm', 'lg', '2x', '3x', '4x', '5x'];

        if ( $iconSize and in_array( $iconSize, $lineAwesomeIconsSizes ) ) {
            $classNames = 'la-' . $iconSize;
        }

        if ( $additionalClass ) {
            $classNames .= ' ' . $additionalClass;
        }

        return <<<HTML
           <i class="la{$iconType} la-{$iconName}  {$classNames}"></i>
        HTML;
    }

    private function showIconWithBoxIcons( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = '' ) : string
    {
        $classNames = '';

        $boxIconsSizes = ['xs', 'sm', 'lg', 'xl'];

        if ( $iconSize and in_array( $iconSize, $boxIconsSizes ) ) {
            $classNames = 'bx-' . $iconSize;
        }

        if ( $additionalClass ) {
            $classNames .= ' ' . $additionalClass;
        }

        return <<<HTML
           <i class="bx bx{$iconType}-{$iconName}  {$classNames}"></i>
        HTML;
    }


    private function showIconWithFontAwesome( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = 'regular' ) : string
    {
        $classNames = '';

        $fontAwesomeIconsSizes = ['2xs', 'xs', 'sm', 'lg', 'xl', '2xl'];

        if ( $iconSize and in_array( $iconSize, $fontAwesomeIconsSizes ) ) {
            $classNames = 'fa-' . $iconSize;
        }

        if ( $additionalClass ) {
            $classNames .= ' ' . $additionalClass;
        }

        return <<<HTML
           <i class="fa-{$iconType} fa-{$iconName}  {$classNames}"></i>
        HTML;
    }

    /**
     * Ajout une class active pour les éléments actifs du menu.
     *
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