<?php

namespace App\Tests\EventSubscriber;

use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use App\Exception\InternalException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use App\EventSubscriber\JsonExceptionSubscriber;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JsonExceptionSubscriberTest extends TestCase
{
    public function testEventSubscription()
    {
        $this->assertArrayHasKey(KernelEvents::EXCEPTION, JsonExceptionSubscriber::getSubscribedEvents());
    }

    public function testOnKernelException()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $params = $this->getMockBuilder(ParameterBagInterface::class)->getMock();

        $subscriber = new JsonExceptionSubscriber($logger, $params);

        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        $event = new ExceptionEvent($kernel, new Request(), 1, new InternalException());

        $logger->expects($this->once())->method('error');

        $subscriberMethod = JsonExceptionSubscriber::getSubscribedEvents()[KernelEvents::EXCEPTION];

        $subscriber->$subscriberMethod($event);
    }
}
