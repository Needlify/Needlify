<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Event;
use App\Factory\EventFactory;
use App\Factory\TopicFactory;
use App\Enum\EventMessageType;
use Zenstruck\Foundry\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EventFixture extends Fixture
{
    public function __construct(
        private UrlGeneratorInterface $router
    ) {
    }

    public function getDependencies()
    {
        return [
            TopicFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        Factory::delayFlush(function () {
            foreach (TopicFactory::all() as $topic) {
                $eventProxy = EventFactory::createOne(['content' => EventMessageType::NEW_TOPIC->format([
                    $topic->object()->getName(),
                    $this->router->generate('app_topic', ['slug' => $topic->object()->getSlug()]),
                ])]);

                /** @var Event $event */
                $event = $eventProxy->object();

                $topic->setEvent($event);
            }
        });
    }
}
