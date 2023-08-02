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

    public function showIcon( string $iconName, ?string $iconSize = null, ?string $additionalClass = null, ?string $iconType = 'regular' ) : string
    {
        return $this->showIconWithLineAwesome( $iconName, $iconSize, $additionalClass );
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