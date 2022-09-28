<?php

namespace App\DataFixtures;

use App\Entity\Moodline;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MoodlineFixture extends Fixture implements DependentFixtureInterface
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
            TagFixture::class,
            TopicFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();
        $topics = $manager->getRepository(Topic::class)->findAll();

        for ($i = 0; $i < 5; ++$i) {
            $moodline = new Moodline();
            $moodline->setContent($this->faker->text(350))
                ->setTopic($this->faker->randomElement($topics))
                ->setTags($this->faker->randomElements($tags, 3))
                ->setAuthor($this->faker->randomElement($users));

            $manager->persist($moodline);
        }

        $manager->flush();
    }
}
