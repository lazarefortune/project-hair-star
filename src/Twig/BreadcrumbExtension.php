<?php

namespace App\Twig;

use App\Service\BreadcrumbService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadcrumbExtension extends AbstractExtension
{
    private BreadcrumbService $breadcrumbService;

    public function __construct( BreadcrumbService $breadcrumbService )
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction( 'render_breadcrumbs', [$this, 'renderBreadcrumbs'] ),
        ];
    }

    /**
     * @return array<array<string, string>>
     */
    public function renderBreadcrumbs() : array
    {
        return $this->breadcrumbService->generateBreadcrumb();
    }
}
