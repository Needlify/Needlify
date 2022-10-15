<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Topic;
use App\Entity\Article;
use App\Entity\Moodline;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    public function testAuthor(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy([]);
        $topic = $this->em->getRepository(Topic::class)->findOneBy([]);

        $moodline = new Moodline();
        $moodline->setAuthor($user);
        $moodline->setTopic($topic);

        $this->em->persist($moodline);
        $this->assertInstanceOf(User::class, $moodline->getAuthor());
        $this->em->remove($moodline);
    }
}
