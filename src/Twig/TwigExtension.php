<?php
namespace App\Twig;

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

    public function showIcon(string $icon, ?string $size = null): string
    {
        $attributes = '';
        if ($size) {
            $attributes = 'la-'. $size;
        }

        return <<<HTML
            <i class="las la-{$icon}  {$attributes}"></i>
        HTML;
    }

//    public function showIcon(string $icon, ?int $size = null): string
//    {
//        $attributes = '';
//        if ($size) {
//            $attributes = ' style="font-size: ' . $size . 'px;"';
//        }
//
//        return <<<HTML
//            <i class="las la-{$icon}" {$attributes}></i>
//        HTML;
//    }

    /**
     * Ajout une class active pour les éléments actifs du menu.
     *
     */
    public function menuActive(array $context, string $route): string
    {
        $active = '';
        dump($context);
        dump($route);
        if (isset($context['_route']) && $context['_route'] === $route) {
            $active = 'active';
        }

        return $active;
    }

}