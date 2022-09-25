<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /**
     * @codeCoverageIgnore
     */
    public function getFilters()
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
