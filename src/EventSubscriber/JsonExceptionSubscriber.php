<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JsonExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ParameterBagInterface $params;

    public function __construct(LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->logger = $logger;
        $this->params = $params;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // Don't do anything if it's not the master request
            return;
        }

        $exception = $event->getThrowable();
        $request = $event->getRequest();

        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        // Check if it is a rest api request
        if ('application/json' === $request->headers->get('Content-Type')) {
            // Customize your response object to display the exception details

            $jsonExceptionResponse = [
                'code' => $exception->getCode(),
            ];

            // If dev env
            if ('dev' === $this->params->get('kernel.environment')) {
                $jsonExceptionResponse['message'] = $exception->getMessage();
                $jsonExceptionResponse['trace'] = $exception->getTrace();
            } else {
                $jsonExceptionResponse['message'] = 'An error occured';
            }

            $response = new JsonResponse($jsonExceptionResponse);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->headers->replace($exception->getHeaders());
            }

            $response->setStatusCode($statusCode);

            // sends the modified response object to the event
            $event->setResponse($response);
        }

        $this->logger->error($exception);
    }
}
