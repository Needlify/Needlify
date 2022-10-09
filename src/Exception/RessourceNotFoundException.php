<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class RessourceNotFoundException extends AbstractHttpException
{
    public const ERROR_CODE = 4000;

    public const HTTP_ERROR_CODE = Response::HTTP_NOT_FOUND;

    // This message will only be displayed on dev environment
    public const MESSAGE = 'Ressource not found. Can not convert route parameter to retrieve $%s of type %s';

    /**
     * @param string[] $messageParams
     */
    public function __construct(array $messageParams = [])
    {
        parent::__construct(vsprintf(self::MESSAGE, $messageParams), self::ERROR_CODE, self::HTTP_ERROR_CODE);
    }
}
