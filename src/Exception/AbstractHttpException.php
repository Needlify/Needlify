<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractHttpException extends HttpException
{
    public function __construct(string $message = '', int $errorCode = 0, int $httpErrorCode = Response::HTTP_INTERNAL_SERVER_ERROR, array $headers = [], ?Throwable $previous = null)
    {
        parent::__construct($httpErrorCode, $message, $previous, $headers, $errorCode);
    }

    public function __toString(): string
    {
        return "[{$this->getCode()} - {$this->getStatusCode()}] : {$this->getMessage()}";
    }
}
