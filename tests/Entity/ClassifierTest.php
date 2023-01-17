<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
    }

    public function testCreatedAt(): void
    {
        $tag = new Tag();
        $tag->setName('tagTest');
        $this->em->persist($tag);
        $this->assertInstanceOf(\DateTimeImmutable::class, $tag->getCreatedAt());
        $this->em->remove($tag);
    }

    public function testLastUseAt(): void
    {
        $tag = new Tag();
        $tag->setName('tagTest');
        $this->em->persist($tag);
        $this->assertInstanceOf(\DateTime::class, $tag->getLastUseAt());
        $this->em->remove($tag);
    }

    public function testSlug(): void
    {
        $tag = new Tag();

        $name = 'tag Test';

        $tag->setName($name);
        $this->em->persist($tag);
        $this->assertMatchesRegularExpression('/tag-test-[a-z0-9]{13}/', $tag->getSlug());
        $this->em->remove($tag);
    }
}
