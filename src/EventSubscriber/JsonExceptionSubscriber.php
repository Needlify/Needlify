<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ParameterBagInterface $params;

    public function __construct(LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->logger = $logger;
        $this->params = $params;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // Don't do anything if it's not the master request
            return;
        }

        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Check if it is a rest api request
        if ('application/json' === $request->headers->get('Content-Type')) {
            // Customize your response object to display the exception details

            $jsonExceptionResponse = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ];

            // If dev env
            if ('dev' === $this->params->get('kernel.environment')) {
                $jsonExceptionResponse['trace'] = $exception->getTrace();
            }

            $response = new JsonResponse($jsonExceptionResponse);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);

            $this->logger->error('API Error [' . $exception->getCode() . '] : ' . $exception->getMessage(), [$exception]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
