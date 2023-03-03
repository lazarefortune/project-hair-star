<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_format', [$this, 'dateFormat']),
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ' ') : string
    {
        $price = number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
        return $price . ' €';
    }

    public function dateFormat(\DateTimeInterface $date, string $format = 'd/m/Y'): string
    {
        return $date->format($format);
    }
}