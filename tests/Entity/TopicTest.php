<?php

namespace App\Tests\Entity;

use App\Entity\Topic;
use App\Entity\Article;
use App\Entity\Publication;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    public function testGetPublication()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $topic = $post->getTopic();
        $this->assertInstanceOf(Collection::class, $topic->getPublications());
        $this->assertInstanceOf(Publication::class, $topic->getPublications()[0]);
    }

    public function testAddPublication()
    {
        $title = 'Test';
        $post = new Article();
        $post->setTitle($title);

        $topic = new Topic();
        $topic->setName('test_tag');

        $topic->addPublication($post);
        $this->assertInstanceOf(Article::class, $topic->getPublications()[0]);
    }

    public function testRemovePublication()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $topic = $post->getTopic();

        $topic->removePublication($post);
        $this->assertNotEquals($topic, $post->getTopic());
    }
}
