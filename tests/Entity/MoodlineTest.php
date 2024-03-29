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

use App\Entity\Moodline;
use App\Enum\ThreadType;
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

    public function testType()
    {
        $content = 'Test';
        $moodline = new Moodline();
        $moodline->setContent($content);
        $this->assertEquals($moodline->getType()->value, 'moodline');
        $this->assertEquals($moodline->getType(), ThreadType::MOODLINE);
    }

    public function testPreview(): void
    {
        $content = 'Test';
        $moodline = new Moodline();
        $moodline->setContent($content);
        $this->assertEquals($content, $moodline->getPreview());
        $this->assertEquals($moodline->getContent(), $moodline->getPreview());
    }
}
