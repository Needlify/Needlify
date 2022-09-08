<?php

namespace App\Tests\Entity;

use App\Entity\Topic;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TopicTest extends KernelTestCase
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
        $topic = new Topic();
        $this->em->persist($topic);
        $this->assertInstanceOf(Uuid::class, $topic->getId());
        $this->em->remove($topic);
    }

    public function testName(): void
    {
        $name = Uuid::v4()->toRfc4122();

        $topic = new Topic();
        $topic->setName($name);
        $this->assertEquals($name, $topic->getName());
        $this->em->persist($topic);

        $topicCopy = new Topic();
        $topicCopy->setName($name);
        $this->em->persist($topicCopy);

        try {
            $this->em->flush($topic);
            $this->em->flush($topicCopy);
        } catch (UniqueConstraintViolationException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail();
    }

    public function testCreatedAt(): void
    {
        $topic = new Topic();
        $this->em->persist($topic);
        $this->assertInstanceOf(DateTimeImmutable::class, $topic->getCreatedAt());
        $this->em->remove($topic);
    }

    public function testLastUseAt(): void
    {
        $topic = new Topic();
        $this->em->persist($topic);
        $this->assertInstanceOf(DateTime::class, $topic->getLastUseAt());
        $this->em->remove($topic);
    }
}
