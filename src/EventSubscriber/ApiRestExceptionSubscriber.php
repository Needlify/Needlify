<?php

namespace App\EventSubscriber;

use App\Exception\Api\Rest\ApiRestExceptionFactory;
use App\Exception\Api\Rest\ApiRestExceptionInterface;
use App\Exception\ApiRestError;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiRestExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // Don't do anything if it's not the master request
            return;
        }

        if (false === \str_contains($event->getRequest()->getRequestUri(), 'api/rest')) {
            // Don't do anything if it's not an API Rest request
            return;
        }

        // You get the exception object from the received event
        $exception = $event->getThrowable();

        $exceptionStatusCode = $exception->getCode();
        if ($exception instanceof HttpException) {
            $exceptionStatusCode = $exception->getStatusCode();
        }

        // Customize your response object to display the exception details
        $response = new JsonResponse();

        if (!$exception instanceof ApiRestExceptionInterface) {
            // We catch the 404 error in case of a No Route Found exception
            if ($exception instanceof NotFoundHttpException) {
                $exception = ApiRestExceptionFactory::createFromCode(
                    ApiRestError::ERR_ROUTE_NOT_FOUND,
                    [$event->getRequest()->getRequestUri()],
                    null,
                    $exception
                );
            } elseif ($exceptionStatusCode >= Response::HTTP_BAD_REQUEST
                && $exceptionStatusCode < Response::HTTP_INTERNAL_SERVER_ERROR
            ) {
                // We got an unhandled client exception so create the right error code and message with a new exception
                $exception = ApiRestExceptionFactory::createFromCode(
                    ApiRestError::ERR_CLIENT_EXCEPTION,
                    [],
                    null,
                    $exception,
                    (0 === $exceptionStatusCode) ? Response::HTTP_BAD_REQUEST : $exceptionStatusCode
                );
            } else {
                // We got an unhandled server exception so create the right error code and message with a new exception
                $exception = ApiRestExceptionFactory::createFromCode(
                    ApiRestError::ERR_SERVER_EXCEPTION,
                    [],
                    null,
                    $exception,
                    (0 === $exceptionStatusCode) ? Response::HTTP_INTERNAL_SERVER_ERROR : $exceptionStatusCode
                );
            }
        }

        // Set the http code and our own error code and message in the content of the response
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setData([
            'code' => $exception->getRestErrorCode(),
            'message' => $exception->getMessage(),
        ]);

        // Send the modified response object to the event
        $event->setResponse($response);

        $this->logger->error('API Rest Error: ' . $exception->getMessage(), [$exception]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
