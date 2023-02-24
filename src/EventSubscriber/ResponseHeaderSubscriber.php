<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResponseHeaderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        // Set multiple headers simultaneously
        $response->headers->add([
            'X-Frame-Options' => 'DENY', // https://infosec.mozilla.org/guidelines/web_security#x-frame-options
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src * data:; frame-src https: youtube.com; child-src https: youtube.com; object-src 'none'; frame-ancestors 'none';", // https://infosec.mozilla.org/guidelines/web_security#content-security-policy
            'X-Content-Type-Options' => 'nosniff', // https://infosec.mozilla.org/guidelines/web_security#x-content-type-options
            'Strict-Transport-Security' => 'max-age=31557600', // https://infosec.mozilla.org/guidelines/web_security#http-strict-transport-security
            'Referrer-Policy' => 'strict-origin-when-cross-origin', // https://infosec.mozilla.org/guidelines/web_security#referrer-policy
            'Access-Control-Allow-Origin' => '*', // https://infosec.mozilla.org/guidelines/web_security#cross-origin-resource-sharing
            'X-XSS-Protection' => '0', // https://infosec.mozilla.org/guidelines/web_security#x-xss-protection
        ]);
    }
}
