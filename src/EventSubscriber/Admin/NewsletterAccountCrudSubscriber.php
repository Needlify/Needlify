<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber\Admin;

use App\Entity\NewsletterAccount;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NewsletterAccountRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class NewsletterAccountCrudSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private NewsletterService $newsletterService,
        private NewsletterAccountRepository $newsletterAccountRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => ['setVerifiedAtAndSendMail'],
            BeforeEntityPersistedEvent::class => ['setVerifiedAtAndSendMail'],
        ];
    }

    public function setVerifiedAtAndSendMail(BeforeEntityUpdatedEvent|BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof NewsletterAccount)) {
            return;
        }

        $previousVersion = null;
        if (null !== $entity->getId()) {
            $wasVerified = $this->newsletterAccountRepository->getIsVerifiedById($entity->getId());
        } else {
            // To generate the Uuid if the account hasn't been fully created yet
            $this->em->persist($entity);
        }

        if (!$entity->getIsVerified() && $entity->getIsVerified() !== $wasVerified) {
            $entity->resetVerifiedAt();
            $this->newsletterService->sendVerificationMail($entity);
        } else {
            $entity->setVerifiedAt();
        }
    }
}