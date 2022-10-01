<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

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
        $event->setMessage('message');
        $this->em->persist($event);
        $this->assertInstanceOf(Uuid::class, $event->getId());
        $this->em->remove($event);
    }

    public function testPublishedAt(): void
    {
        $event = new Event();
        $event->setMessage('message');
        $this->em->persist($event);
        $this->assertInstanceOf(DateTimeInterface::class, $event->getPublishedAt());
        $this->em->remove($event);
    }

    public function testAuthor(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy([]);
        $event = new Event();
        $event->setMessage('message');
        $event->setAuthor($user);

        $this->em->persist($event);
        $this->assertInstanceOf(User::class, $event->getAuthor());
        $this->em->remove($event);
    }
}