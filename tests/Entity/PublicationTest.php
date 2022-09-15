<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class PublicationTest extends KernelTestCase
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
        $article = new Article();
        $title = 'Test';
        $article->setTitle($title);
        $this->em->persist($article);
        $this->assertInstanceOf(Uuid::class, $article->getId());
        $this->em->remove($article);
    }

    public function testAuthor(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy([]);
        $article = new Article();

        $article->setAuthor($user);
        $this->assertInstanceOf(User::class, $article->getAuthor());
        $this->assertEquals($user, $article->getAuthor());
        $this->em->remove($article);
    }

    public function testPublishedAt(): void
    {
        $article = new Article();
        $title = 'Test';
        $article->setTitle($title);
        $this->em->persist($article);
        $this->assertInstanceOf(DateTimeImmutable::class, $article->getPublishedAt());
        $this->em->remove($article);
    }

    public function testTopic(): void
    {
        $topic = $this->em->getRepository(Topic::class)->findOneBy([]);
        $article = new Article();
        $article->setTopic($topic);
        $this->assertInstanceOf(Topic::class, $article->getTopic());
        $this->assertEquals($topic, $article->getTopic());
    }

    public function testTags(): void
    {
        $allTags = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 2, 0);
        $first = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 1, 0);
        $last = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC'], 1, 1);

        $article = new Article();
        $article->setTags($first);

        $this->assertInstanceOf(ArrayCollection::class, $article->getTags());
        $this->assertEquals(new ArrayCollection($first), $article->getTags());

        $article->addTag($last[0]);
        $this->assertEquals(new ArrayCollection($allTags), $article->getTags());

        $article->removeTag($last[0]);
        $this->assertEquals(new ArrayCollection($first), $article->getTags());
    }
}
