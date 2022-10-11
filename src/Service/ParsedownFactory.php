<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Parsedown;

class ParsedownFactory
{
    public static function create(): Parsedown
    {
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);

        return $Parsedown;
    }
}
