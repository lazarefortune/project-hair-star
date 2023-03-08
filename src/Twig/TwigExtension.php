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
        ];
    }

    public function showIcon(string $icon, ?int $size = null): string
    {
        $attributes = '';
        if ($size) {
            $attributes = ' style="font-size: ' . $size . 'px"';
        }

        return <<<HTML
            <i class="las la-{$icon}" {$attributes}></i>
        HTML;
    }


}