<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Logger;

use App\Entity\User;
use Monolog\LogRecord;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class ExtraLogProcessor
{
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(Security $security, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function __invoke(LogRecord $record)
    {
        $serverParams = $this->requestStack->getCurrentRequest()->server ?? null;

        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $record['extra']['method'] = $serverParams?->has('REQUEST_METHOD') ? $serverParams->get('REQUEST_METHOD') : '-';
        $record['extra']['protocol'] = $serverParams?->has('SERVER_PROTOCOL') ? $serverParams->get('SERVER_PROTOCOL') : '-';
        $record['extra']['agent'] = $serverParams?->has('HTTP_USER_AGENT') ? $serverParams->get('HTTP_USER_AGENT') : '-';
        $record['extra']['url'] = $serverParams?->has('REQUEST_URI') ? $serverParams->get('REQUEST_URI') : '-';
        $record['extra']['ip'] = $serverParams?->has('REMOTE_ADDR') ? $serverParams->get('REMOTE_ADDR') : '-';
        $record['extra']['user'] = null !== $currentUser ? $currentUser->getUsername() : '-';

        return $record;
    }
}
