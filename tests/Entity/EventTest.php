<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testMessage(): void
    {
        $message = 'Test';
        $event = new Event();
        $event->setMessage($message);
        $this->assertEquals($message, $event->getMessage());
    }
}
