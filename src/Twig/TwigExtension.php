<?php
namespace App\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon' , $this->showIcon(...), ['is_safe' => ['html']]),
            new TwigFunction('menu_active', $this->menuActive(...), ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    public function showIcon(string $icon, ?string $size = null, ?string $attrs = null): string
    {
        $attributes = '';
        if ($size) {
            $attributes = 'la-'. $size;
        }

        if ($attrs) {
            $attributes .= ' ' . $attrs;
        }

        return <<<HTML
            <i class="las la-{$icon}  {$attributes}"></i>
        HTML;
    }

    /**
     * Ajout une class active pour les éléments actifs du menu.
     *
     */
    public function menuActive(array $context, string $route): string
    {
        $active = '';
        $request = $context['app']->getRequest();
        $currentRoute = $request->get('_route');

        if ($currentRoute === $route) {
            $active = 'active';
        }

        return $active;
    }

}