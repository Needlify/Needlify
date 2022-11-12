<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Service\ThreadType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->resetManager();
    }

    public function testTitle(): void
    {
        $title = 'Test';
        $article = new Article();
        $article->setTitle($title);
        $this->assertEquals($title, $article->getTitle());
    }

    public function testDescription(): void
    {
        $description = 'Test';
        $article = new Article();
        $article->setDescription($description);
        $this->assertEquals($description, $article->getDescription());
    }

    public function testContent(): void
    {
        $content = 'Test';
        $article = new Article();
        $article->setContent($content);
        $this->assertEquals($content, $article->getContent());
    }

    public function testViews(): void
    {
        $article = new Article();
        $title = 'Test';
        $article->setTitle($title);

        $this->em->persist($article);
        $this->assertEquals(0, $article->getViews());

        $article->incrementViews();
        $this->assertEquals(1, $article->getViews());
        $this->em->remove($article);
    }

    public function testSlug(): void
    {
        $article = new Article();

        $title = 'Hello World';

        $article->setTitle($title);
        $this->em->persist($article);

        $this->assertMatchesRegularExpression('/hello-world-[a-z0-9]{13}/', $article->getSlug());
        $this->em->remove($article);
    }

    public function testType()
    {
        $title = 'Test';
        $article = new Article();
        $article->setTitle($title);
        $this->assertEquals($article->getType(), 'article');
        $this->assertEquals($article->getType(), ThreadType::ARTICLE);
    }

    public function testPreview(): void
    {
        $description = 'Test';
        $article = new Article();
        $article->setDescription($description);
        $this->assertEquals($description, $article->getPreview());
        $this->assertEquals($article->getDescription(), $article->getPreview());
    }
}
