<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Parsedown;

class ParsedownFactory
{
    public static function create(): FluidTagParsedown
    {
        $Parsedown = new FluidTagParsedown();
        $Parsedown
            ->setSafeMode(true)
            ->setBreaksEnabled(true)
            ->setUrlsLinked(false);

        return $Parsedown;
    }
}
