<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Topic;
use App\Entity\Moodline;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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
