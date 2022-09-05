<?php

namespace App\Exception\Api\Rest;

use App\Exception\ApiRestError;

class ApiRestExceptionFactory
{
    /**
     * @param array|string|null $message
     */
    public static function createFromCode(
        ApiRestError $apiRestError,
        array $parameters = [],
        array|string|null $message = null,
        ?\Throwable $previousException = null,
        ?int $httpCode = null
    ) {
        $errorMessage = self::computeErrorMessage($apiRestError, $parameters, $message);

        return match ($apiRestError) {
            ApiRestError::ERR_ROUTE_NOT_FOUND => new RouteNotFoundException(
                $apiRestError,
                $errorMessage,
                $previousException
            ),
            ApiRestError::ERR_INVALID_DATA => new InvalidDataException(
                $apiRestError,
                $errorMessage,
                $previousException
            ),
            ApiRestError::ERR_RESOURCE_NOT_FOUND => new ResourceNotFoundException(
                $apiRestError,
                $errorMessage,
                $previousException
            ),
            ApiRestError::ERR_CLIENT_EXCEPTION => new ClientException(
                $apiRestError,
                $errorMessage,
                $previousException,
                $httpCode
            ),
            ApiRestError::ERR_SERVER_EXCEPTION => new ServerException(
                $apiRestError,
                $errorMessage,
                $previousException,
                $httpCode
            ),
        };
    }

    private static function computeErrorMessage(
        ApiRestError $restErrorCode,
        array $parameters = [],
        ?string $message = null
    ): string {
        $errorMessage = \is_null($message) ? $restErrorCode->getErrorMessage() : $message;
        if (\count($parameters) > 0) {
            $errorMessage = \vsprintf($errorMessage, $parameters);
        }

        return $errorMessage;
    }
}
