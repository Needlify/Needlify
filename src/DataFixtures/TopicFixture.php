<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
