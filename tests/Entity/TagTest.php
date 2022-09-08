<?php

namespace App\Tests\Entity;

use App\Entity\Tag;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TagTest extends KernelTestCase
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
        $tag = new Tag();
        $this->em->persist($tag);
        $this->assertInstanceOf(Uuid::class, $tag->getId());
        $this->em->remove($tag);
    }

    public function testName(): void
    {
        $name = Uuid::v4()->toRfc4122();

        $tag = new Tag();
        $tag->setName($name);
        $this->assertEquals($name, $tag->getName());
        $this->em->persist($tag);

        $tagCopy = new Tag();
        $tagCopy->setName($name);
        $this->em->persist($tagCopy);

        try {
            $this->em->flush($tag);
            $this->em->flush($tagCopy);
        } catch (UniqueConstraintViolationException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail();
    }

    public function testCreatedAt(): void
    {
        $tag = new Tag();
        $this->em->persist($tag);
        $this->assertInstanceOf(DateTimeImmutable::class, $tag->getCreatedAt());
        $this->em->remove($tag);
    }

    public function testLastUsedAt(): void
    {
        $tag = new Tag();
        $this->em->persist($tag);
        $this->assertInstanceOf(DateTime::class, $tag->getLastUsedAt());
        $this->em->remove($tag);
    }
}
