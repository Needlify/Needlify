<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber\admin;

use App\Entity\Event;
use App\Entity\Moodline;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;

class RawContentHandlerSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_ENTITY = [Event::class, Moodline::class];

    private const ALLOWED_ACTION = [Action::EDIT];

    public static function getSubscribedEvents()
    {
        return [
            BeforeCrudActionEvent::class => ['setRawContent'],
        ];
    }

    /**
     * Permet de set de raw message dans le cas d'un update car il n'est pas set par défaut étant donné que ce n'est pas un champ doctrine.
     *
     * @return void
     */
    public function setRawContent(BeforeCrudActionEvent $event)
    {
        $action = $event->getAdminContext()->getCrud()->getCurrentAction();
        $entityFqcn = $event->getAdminContext()->getEntity()->getFqcn();

        if (in_array($action, self::ALLOWED_ACTION) && in_array($entityFqcn, self::ALLOWED_ENTITY)) {
            $entity = $event->getAdminContext()->getEntity()->getInstance();
            $entity->setRawContent($entity->getContent());
        }
    }
}
