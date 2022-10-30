<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $passwordHasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setUsername($this->faker->userName)
                 ->setEmail($this->faker->email);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'password'
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('MrAnyx')
            ->setEmail('example@mail.com');

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'totoblablabla1A!'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
