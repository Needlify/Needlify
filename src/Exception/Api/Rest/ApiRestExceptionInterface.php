<?php

namespace App\Exception\Api\Rest;

interface ApiRestExceptionInterface
{
    public function getRestErrorCode(): int;
}
