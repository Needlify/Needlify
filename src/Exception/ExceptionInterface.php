<?php

namespace App\Exception;

/**
 * @deprecated
 */
interface ExceptionInterface
{
    public function getErrorCode(): int;

    public function getStatusCode(): int;

    public function getMessageFormat(): string;
}
