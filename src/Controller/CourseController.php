<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CourseController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/course/{slug}', name: 'app_course', methods: ['GET'], options: ['expose' => true])]
    public function article(Course $course, LessonRepository $lessonRepository): Response
    {
        if ($course->isPrivate()) {
            throw ExceptionFactory::throw(NotFoundHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'This ressource is not accessible');
        }

        $lesson = $lessonRepository->findOneBy(['title' => 'Commodi dolor quia et incidunt vero quisquam. Debitis deleniti et maiores.']);

        // $course->addLesson($lesson);
        // $course->incrementViews();

        // $this->em->persist($course);
        // $this->em->flush();

        dd($course, $lesson);

        return $this->render('pages/course/course.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/course/{course_slug}/{lesson_slug}', name: 'app_lesson', methods: ['GET'], options: ['expose' => true])]
    #[ParamConverter('course', options: ['mapping' => ['course_slug' => 'slug']])]
    #[ParamConverter('lesson', options: ['mapping' => ['lesson_slug' => 'slug']])]
    public function lesson(Course $course, Lesson $lesson): Response
    {
        dd($course, $lesson);

        if ($course->isPrivate() || $lesson->isPrivate()) {
            throw ExceptionFactory::throw(NotFoundHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'This ressource is not accessible');
        }

        $course->incrementViews();
        $lesson->incrementViews();

        $this->em->persist($course);
        $this->em->persist($lesson);
        $this->em->flush();

        return $this->render('pages/course/lesson.html.twig', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }
}
