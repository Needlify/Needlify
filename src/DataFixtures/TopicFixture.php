<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TopicFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $topicName = ['topic1', 'topic2', 'topic3'];
        foreach ($topicName as $name) {
            $tag = new Topic();
            $tag->setName($name);

            $manager->persist($tag);
        }
        $manager->flush();
    }
}
