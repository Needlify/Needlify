<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

class PopularityCalculatorService
{
    public const ALPHA = 2;
    public const BETA = 0.85;

    public static function calculatePopularity(int $views, \DateTimeImmutable $publishedAt, int $round = 2): float
    {
        // $now = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->getTimestamp();
        $now = (new \DateTimeImmutable('now'))->getTimestamp();
        $date = $publishedAt->getTimestamp();

        $computedViews = $views + 1;

        $firstFactor = fdiv(sqrt($computedViews), $computedViews);
        $secondFactor = fdiv(self::ALPHA * log($computedViews), 1 + log(1 + (self::BETA * ($now - $date))));

        $popularity = $firstFactor + $secondFactor;

        return round($popularity, $round);
    }
}
