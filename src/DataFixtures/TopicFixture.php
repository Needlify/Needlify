<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
