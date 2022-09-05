<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

enum ApiRestError: int
{
    case ERR_ROUTE_NOT_FOUND = 2003;
    case ERR_INVALID_DATA = 4003;
    case ERR_RESOURCE_NOT_FOUND = 5001;
    case ERR_CLIENT_EXCEPTION = 7001;
    case ERR_SERVER_EXCEPTION = 8001;

    public function getErrorMessage(): string
    {
        return match ($this) {
            self::ERR_ROUTE_NOT_FOUND => 'Unknown route %s',
            self::ERR_INVALID_DATA => '%s is invalid',
            self::ERR_RESOURCE_NOT_FOUND => "%s with %s '%s' not found",
            self::ERR_CLIENT_EXCEPTION => 'A client error has occured',
            self::ERR_SERVER_EXCEPTION => 'A server error has occured',
        };
    }

    /**
     * These are the default HTTP codes corresponding to our own Rest error codes.
     * For ERR_7001 and ERR_8001, when we create the exceptions in the ApiRestExceptionListener, we take the HTTP code from the previous exception.
     */
    public function getErrorHttpCode(): int
    {
        return match ($this) {
            self::ERR_ROUTE_NOT_FOUND, self::ERR_RESOURCE_NOT_FOUND => Response::HTTP_NOT_FOUND,
            self::ERR_INVALID_DATA, self::ERR_CLIENT_EXCEPTION => Response::HTTP_BAD_REQUEST,
            self::ERR_SERVER_EXCEPTION => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
