<?php

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
