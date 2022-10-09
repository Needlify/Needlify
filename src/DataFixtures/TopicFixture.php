<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TopicFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $topics = ['topic1', 'topic2', 'topic3', 'topic4', 'topic5'];
        foreach ($topics as $topicName) {
            $topic = new Topic();
            $topic->setName($topicName);

            $manager->persist($topic);
        }
        $manager->flush();
    }
}
