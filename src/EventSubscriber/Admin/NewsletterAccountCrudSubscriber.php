<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber\Admin;

use App\Entity\NewsletterAccount;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class NewsletterAccountCrudSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => ['setVerifiedAt'],
            BeforeEntityPersistedEvent::class => ['setVerifiedAt'],
        ];
    }

    public function setVerifiedAt(BeforeEntityUpdatedEvent|BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof NewsletterAccount)) {
            return;
        }

        if ($entity->getIsVerified()) {
            $entity->setVerifiedAt();
        } else {
            $entity->resetVerifiedAt();
        }
    }
}
