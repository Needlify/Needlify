<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

abstract class AbstractHttpException extends HttpException implements HttpExceptionInterface
{
    public function __construct(string $message = '', int $errorCode = 0, int $httpErrorCode = Response::HTTP_INTERNAL_SERVER_ERROR, array $headers = [], ?Throwable $previous = null)
    {
        parent::__construct($httpErrorCode, $message, $previous, $headers, $errorCode);
    }
}
