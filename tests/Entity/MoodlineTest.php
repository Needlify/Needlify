<?php

namespace App\Tests\Entity;

use App\Entity\Moodline;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class MoodlineTest extends KernelTestCase
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
        $moodline = new Moodline();
        $this->em->persist($moodline);
        $this->assertInstanceOf(Uuid::class, $moodline->getId());
        $this->em->remove($moodline);
    }

    public function testContent(): void
    {
        $content = 'Test';
        $moodline = new Moodline();
        $moodline->setContent($content);
        $this->assertEquals($content, $moodline->getContent());
    }

    public function testAuthor(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy([]);
        $moodline = new Moodline();

        $moodline->setAuthor($user);
        $this->assertInstanceOf(User::class, $moodline->getAuthor());
        $this->assertEquals($user, $moodline->getAuthor());
        $this->em->remove($moodline);
    }

    public function testPublishedAt(): void
    {
        $moodline = new Moodline();
        $this->em->persist($moodline);
        $this->assertInstanceOf(DateTimeImmutable::class, $moodline->getPublishedAt());
        $this->em->remove($moodline);
    }

    public function testTopic(): void
    {
        $topic = $this->em->getRepository(Topic::class)->findOneBy([]);
        $moodline = new Moodline();
        $moodline->setTopic($topic);
        $this->assertInstanceOf(Topic::class, $moodline->getTopic());
        $this->assertEquals($topic, $moodline->getTopic());
    }

    public function testTags(): void
    {
        $allTags = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 2, 0);
        $first = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 1, 0);
        $last = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 1, 1);

        $moodline = new Moodline();
        $moodline->setTags($first);

        $this->assertInstanceOf(ArrayCollection::class, $moodline->getTags());
        $this->assertEquals(new ArrayCollection($first), $moodline->getTags());

        $moodline->addTag($last[0]);
        $this->assertEquals(new ArrayCollection($allTags), $moodline->getTags());
    }
}
