<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Thread;
use DateTimeImmutable;
use App\Entity\Article;
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
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());
        $this->em->remove($user);
    }

    public function testGetThreads()
    {
        /** @var Article $post */
        $post = $this->em->getRepository(Article::class)->findOneBy([]);
        $author = $post->getAuthor();

        $this->assertInstanceOf(Collection::class, $author->getThreads());
        $this->assertInstanceOf(Thread::class, $author->getThreads()[0]);
        $this->assertContains($post, $author->getThreads());
    }

    public function testAddThread()
    {
        $article = new Article();
        $article->setTitle('test');
        $user = $this->em->getRepository(User::class)->findOneBy([]);

        $user->addThread($article);
        $this->assertInstanceOf(Collection::class, $user->getThreads());
        $this->assertInstanceOf(Thread::class, $user->getThreads()[0]);
        $this->assertContains($article, $user->getThreads());
    }

    public function testRemoveThread()
    {
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy([]);
        $article = $user->getThreads()[0];

        $user->removeThread($article);
        $this->assertNotContains($article, $user->getThreads());
    }
}
