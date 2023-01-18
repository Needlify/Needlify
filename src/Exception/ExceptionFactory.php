<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

class ExceptionFactory
{
    public static function throw($exception, ExceptionCode $code, string $message = 'An error occured', array $messageParams = [])
    {
        return new $exception(message: vsprintf($message, $messageParams), code: $code->value);
    }
}
