<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Logger;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

class ExtraLogProcessor
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function __invoke(LogRecord $record)
    {
        $request = $this->requestStack->getCurrentRequest();
        $serverParams = $request?->server ?? null;

        $record['extra']['method'] = $serverParams?->has('REQUEST_METHOD') ? $serverParams?->get('REQUEST_METHOD') : '-';
        $record['extra']['agent'] = $serverParams?->has('HTTP_USER_AGENT') ? $serverParams?->get('HTTP_USER_AGENT') : '-';
        $record['extra']['url'] = $serverParams?->has('REQUEST_URI') ? $serverParams?->get('REQUEST_URI') : '-';
        $record['extra']['ip'] = $serverParams?->has('REMOTE_ADDR') ? $serverParams?->get('REMOTE_ADDR') : '-';

        return $record;
    }
}
