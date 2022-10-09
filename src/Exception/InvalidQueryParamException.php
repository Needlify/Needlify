<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidQueryParamException extends AbstractHttpException
{
    public const ERROR_CODE = 4001;

    public const HTTP_ERROR_CODE = Response::HTTP_BAD_REQUEST;

    // This message will only be displayed on dev environment
    public const MESSAGE = 'Invalid query parameter';

    /**
     * @param string[] $messageParams
     */
    public function __construct(array $messageParams = [])
    {
        parent::__construct(vsprintf(self::MESSAGE, $messageParams), self::ERROR_CODE, self::HTTP_ERROR_CODE);
    }
}
