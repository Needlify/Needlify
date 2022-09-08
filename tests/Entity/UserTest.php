<?php

namespace App\Tests\Entity;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

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
}
