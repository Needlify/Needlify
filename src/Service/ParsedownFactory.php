<?php

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
