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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ThreadTest extends KernelTestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->resetManager();
    }

    public function testId(): void
    {
        $event = new Event();
        $event->setContent('message');
        $this->em->persist($event);
        $this->assertInstanceOf(Uuid::class, $event->getId());
        $this->em->remove($event);
    }

    public function testPublishedAt(): void
    {
        $event = new Event();
        $event->setContent('message');
        $this->em->persist($event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getPublishedAt());
        $this->em->remove($event);
    }
}
