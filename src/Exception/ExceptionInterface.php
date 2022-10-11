<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
