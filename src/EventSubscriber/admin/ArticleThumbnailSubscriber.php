<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber\admin;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ArticleThumbnailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['setDefaultImage'],
            BeforeEntityPersistedEvent::class => ['setDefaultImage'],
        ];
    }

    /**
     * Permet de set de raw message dans le cas d'un update car il n'est pas set par défaut étant donné que ce n'est pas un champ doctrine.
     *
     * @return void
     */
    public function setDefaultImage(BeforeEntityUpdatedEvent|BeforeEntityPersistedEvent $event)
    {
        // dd($event);
        // dd($event->getEntity());
        // $action = $event->getAdminContext()->getCrud()->getCurrentAction();
        // $entityFqcn = $event->getAdminContext()->getEntity()->getFqcn();

        // if (in_array($action, self::ALLOWED_ACTION) && in_array($entityFqcn, self::ALLOWED_ENTITY)) {
        //     $entity = $event->getAdminContext()->getEntity()->getInstance();
        //     $entity->setRawContent($entity->getContent());
        // }
    }
}
