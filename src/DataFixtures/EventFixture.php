<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Topic;
use App\Entity\User;
use App\Service\EventMessage;
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
            TopicFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $topics = $manager->getRepository(Topic::class)->findAll();

        /** @var $topics Topic[] */
        foreach ($topics as $topic) {
            $event = new Event();
            $event->setMessage(EventMessage::NEW_TOPIC->format([$topic->getName()]))
                ->setAuthor($this->faker->randomElement($users));

            $manager->persist($event);
        }

        $manager->flush();
    }
}
