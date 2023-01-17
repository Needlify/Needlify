<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testMessage(): void
    {
        $content = 'Test';
        $event = new Event();
        $event->setContent($content);
        $this->assertEquals($content, $event->getContent());
    }
}
