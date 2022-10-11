<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InternalException extends AbstractHttpException
{
    public const ERROR_CODE = 5000;

    public const HTTP_ERROR_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    // This message will only be displayed on dev environment
    public const MESSAGE = 'An error occured';

    /**
     * @param string[] $messageParams
     */
    public function __construct(array $messageParams = [])
    {
        parent::__construct(vsprintf(self::MESSAGE, $messageParams), self::ERROR_CODE, self::HTTP_ERROR_CODE);
    }
}
