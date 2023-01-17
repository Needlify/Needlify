<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber\Admin;

use App\Entity\Event;
use App\Entity\Topic;
use App\Enum\EventMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class EventCreationSubscriber implements EventSubscriberInterface
{
    private UrlGeneratorInterface $router;
    private EntityManagerInterface $em;

    public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => 'createEvent',
        ];
    }

    public function createEvent(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Topic) {
            $event = new Event();
            $event->setContent(EventMessage::NEW_TOPIC->format([
                $entity->getName(),
                $this->router->generate('app_topic', ['slug' => $entity->getSlug()]),
            ]));

            $entity->setEvent($event);

            // Event is automatically persisted by Doctrine
            $this->em->persist($entity);
            $this->em->flush();
        }
    }
}
