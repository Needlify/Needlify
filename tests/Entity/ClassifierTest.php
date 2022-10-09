<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Tag;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ClassifierTest extends KernelTestCase
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
        $tag->setName('tagTest');
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
        $tag->setName('tagTest');
        $this->em->persist($tag);
        $this->assertInstanceOf(DateTimeImmutable::class, $tag->getCreatedAt());
        $this->em->remove($tag);
    }

    public function testLastUseAt(): void
    {
        $tag = new Tag();
        $tag->setName('tagTest');
        $this->em->persist($tag);
        $this->assertInstanceOf(DateTime::class, $tag->getLastUseAt());
        $this->em->remove($tag);
    }

    public function testSlug(): void
    {
        $tag = new Tag();

        $name = 'tag Test';

        $tag->setName($name);
        $this->em->persist($tag);
        $this->assertEquals('tag-test', $tag->getSlug());
        $this->em->remove($tag);
    }
}
