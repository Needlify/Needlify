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

use App\Entity\User;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * This subscriber is used to hash the password before persisting the user.
 * This is needed because the plain password is not persisted in the database.
 * Is modifies the submitted data before the form is validated.
 */
class UserCrudPreSubmitSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $encoder
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => ['preSubmit'],
        ];
    }

    public function preSubmit(PreSubmitEvent $event): void
    {
        /** @var User $entity */
        $entity = $event->getForm()->getData();

        if (!$entity instanceof User) {
            return;
        }

        $data = $event->getData();

        $plainPassword = $data['password']['first'];
        $entityPassword = $entity->getPassword();

        if (empty($plainPassword)) {
            $password = $entityPassword;
            $rawPassword = $plainPassword;
        } else {
            $password = $this->encoder->hashPassword(
                $entity,
                $plainPassword
            );
            $rawPassword = $plainPassword;
        }

        $event->setData(array_merge($data, [
            'password' => [
                'first' => $password,
                'second' => $password,
            ],
            'rawPassword' => $rawPassword,
        ]));
    }
}
