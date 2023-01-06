<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Publication;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testId(): void
    {
        $user = new User();
        $this->em->persist($user);
        $this->assertInstanceOf(Uuid::class, $user->getId());
        $this->em->remove($user);
    }

    public function testEmail(): void
    {
        $email = 'example@email.com';
        $user = new User();
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testUsername(): void
    {
        $username = 'John Doe';
        $user = new User();
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword(): void
    {
        $password = 'super_secret_password';
        $user = new User();
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testRoles(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_TEST', ...$user->getRoles()]);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_TEST', $user->getRoles());

        $user->addRole('ROLE_TEST_2');
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_TEST', $user->getRoles());
        $this->assertContains('ROLE_TEST_2', $user->getRoles());
    }

    public function testCreatedAt(): void
    {
        $user = new User();
        $this->em->persist($user);
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->em->remove($user);
    }

    public function testGetPublications()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $author = $post->getAuthor();

        $this->assertInstanceOf(Collection::class, $author->getPublications());
        $this->assertInstanceOf(Publication::class, $author->getPublications()[0]);
        $this->assertContains($post, $author->getPublications());
    }

    public function testAddPublication()
    {
        $article = new Article();
        $article->setTitle('test');
        $user = $this->em->getRepository(User::class)->findOneBy([]);

        $user->addPublication($article);
        $this->assertInstanceOf(Collection::class, $user->getPublications());
        $this->assertInstanceOf(Publication::class, $user->getPublications()[0]);
        $this->assertContains($article, $user->getPublications());
    }

    public function testRemovePublication()
    {
        /** @var Publication $publication */
        $publication = $this->em->getRepository(Publication::class)->findOneBy([]);
        $author = $publication->getAuthor();

        $author->removePublication($publication);
        $this->assertNotContains($publication, $author->getPublications());
    }
}
