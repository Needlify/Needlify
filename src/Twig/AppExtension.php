<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            new TwigFilter('formatNumber', [$this, 'formatNumber']),
        ];
    }

    public function isInstanceof($element, $meta): bool
    {
        return $element instanceof $meta;
    }

    public function formatNumber($num)
    {
        if ($num > 1_000_000_000_000) {
            return round($num / 1_000_000_000_000, 1) . 'T';
        } elseif ($num > 1_000_000_000) {
            return round($num / 1_000_000_000, 1) . 'B';
        } elseif ($num > 1_000_000) {
            return round($num / 1_000_000, 1) . 'M';
        } elseif ($num > 1_000) {
            return round($num / 1_000, 1) . 'K';
        }

        return $num;
    }
}
