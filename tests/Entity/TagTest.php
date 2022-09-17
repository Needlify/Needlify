<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Entity\Publication;
use App\Entity\Tag;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    public function testGetPublication()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $tag = $post->getTags()[0];
        $this->assertInstanceOf(Collection::class, $tag->getPublications());
        $this->assertInstanceOf(Publication::class, $tag->getPublications()[0]);
    }

    public function testAddPublication()
    {
        $tag = new Tag();
        $tag->setName('test_tag');
        $post = $this->em->getRepository(Article::class)->findOneBy([]);

        $tag->addPublication($post);
        $this->assertInstanceOf(Article::class, $tag->getPublications()[0]);
    }

    public function testRemovePublication()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $tag = $post->getTags()[0];

        $tag->removePublication($post);
        $this->assertNotContains($tag, $post->getTags());
    }
}
