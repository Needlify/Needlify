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

use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class CourseEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'persistLesson',
            BeforeEntityUpdatedEvent::class => 'updateLesson',
            AfterEntityDeletedEvent::class => 'deleteLesson',
        ];
    }

    private function removeFromCourse(Lesson $lesson)
    {
        $lesson->getPrevious()?->setNext($lesson->getNext());
        $lesson->getNext()?->setPrevious($lesson->getPrevious());
        $lesson->resetLink();
    }

    private function addToCourse(Lesson $lesson, Course $course)
    {
        if ($course->getLessons()->count() > 0) {
            $lesson->setPrevious($course->getLastLesson());
            $course->getLastLesson()->setNext($lesson);
        }
    }

    public function persistLesson(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Lesson) {
            $course = $entity->getCourse();

            if (null !== $course) {
                $this->addToCourse($entity, $course);
            }
        }
    }

    public function updateLesson(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Lesson) {
            $course = $entity->getCourse();
            $originalCourse = $this->em->getRepository(Lesson::class)->getOriginalCourse($entity);

            // La gestion de la relation avec l'entity Course est gérée par EasyAdmin
            // Ici, on ne gère que les liens entre les champs next et previous
            if ($course !== $originalCourse) {
                if (null === $course) {
                    $this->removeFromCourse($entity);
                } else {
                    $this->removeFromCourse($entity);
                    $this->addToCourse($entity, $course);
                }
            }
        }
    }

    public function deleteLesson(AfterEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Lesson) {
            $this->removeFromCourse($entity);

            // On flush car on est dans l'event After
            $this->em->flush();
        }
    }
}
