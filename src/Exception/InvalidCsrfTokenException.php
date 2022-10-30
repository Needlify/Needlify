<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidCsrfTokenException extends AbstractHttpException
{
    public const ERROR_CODE = 4002;

    public const HTTP_ERROR_CODE = Response::HTTP_BAD_REQUEST;

    // This message will only be displayed on dev environment
    public const MESSAGE = 'Invalid CSRF token';

    /**
     * @param string[] $messageParams
     */
    public function __construct(array $messageParams = [])
    {
        parent::__construct(vsprintf(self::MESSAGE, $messageParams), self::ERROR_CODE, self::HTTP_ERROR_CODE);
    }
}
