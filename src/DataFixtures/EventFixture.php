<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixture extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 5; ++$i) {
            $moodline = new Event();
            $moodline->setMessage($this->faker->sentence())
                ->setAuthor($this->faker->randomElement($users));

            $manager->persist($moodline);
        }

        $manager->flush();
    }
}
