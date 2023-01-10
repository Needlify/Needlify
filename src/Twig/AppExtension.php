<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Twig\TwigFilter;
use App\Service\ParsedownFactory;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    /**
     * @codeCoverageIgnore
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_number', [$this, 'formatNumber']),
            new TwigFilter('cases', [$this, 'enumCases']),
            new TwigFilter('time_to_read', [$this, 'timeToRead']),
            new TwigFilter('markdown', [$this, 'markdown']),
            new TwigFilter('hideEmail', [$this, 'hideEmail']),
        ];
    }

    public function hideEmail(string $email)
    {
        $emailList = explode('@', $email);
        $lengthMailFirstPart = strlen($emailList[0]);

        if ($lengthMailFirstPart > 2) {
            return substr($emailList[0], 0, 2) . str_repeat('•', $lengthMailFirstPart - 2) . "@{$emailList[1]}";
        } else {
            return str_repeat('•', $lengthMailFirstPart) . "@{$emailList[1]}";
        }
    }

    public function markdown($content): string
    {
        return ParsedownFactory::create()->text($content);
    }

    public function enumCases(string $enumFqcn): array
    {
        if (!enum_exists($enumFqcn)) {
            return [];
        }

        return $enumFqcn::cases();
    }

    public function formatNumber($num): string
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

    public function timeToRead(string $content): int
    {
        return (int) ceil(str_word_count($content) / 225);
    }
}
