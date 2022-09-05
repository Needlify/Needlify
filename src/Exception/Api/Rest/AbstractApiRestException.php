<?php

namespace App\Exception\Api\Rest;

use App\Exception\ApiRestError;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AbstractApiRestException extends HttpException implements ApiRestExceptionInterface
{
    protected int $restErrorCode;

    public function __construct(
        ApiRestError $apiRestError,
        string $message,
        ?\Throwable $previousException = null,
        ?int $httpCode = null
    ) {
        $this->restErrorCode = $apiRestError->value;

        parent::__construct($httpCode ?? $apiRestError->getErrorHttpCode(), $message, $previousException);
    }

    public function getRestErrorCode(): int
    {
        return $this->restErrorCode;
    }
}
