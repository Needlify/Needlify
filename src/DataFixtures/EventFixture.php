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
use App\Enum\EventMessage;
use App\Factory\EventFactory;
use App\Factory\TopicFactory;
use Zenstruck\Foundry\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EventFixture extends Fixture
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
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
                $eventProxy = EventFactory::createOne(['content' => EventMessage::NEW_TOPIC->format([
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
