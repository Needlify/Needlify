<?php

namespace App\Tests\Entity;

use App\Entity\Moodline;
use PHPUnit\Framework\TestCase;

class MoodlineTest extends TestCase
{
    public function testContent(): void
    {
        $content = 'Test';
        $moodline = new Moodline();
        $moodline->setContent($content);
        $this->assertEquals($content, $moodline->getContent());
    }
}
