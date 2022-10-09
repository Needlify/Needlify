<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    /**
     * @codeCoverageIgnore
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('instanceOf', [$this, 'isInstanceof']),
        ];
    }

    public function isInstanceof($element, $meta): bool
    {
        return $element instanceof $meta;
    }
}
