<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Course;
use Symfony\Component\Uid\Uuid;
use App\Service\Admin\CourseLinker;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\Crud\CourseCrudController;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CourseAdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/course/{id}/sort', 'admin_course_sort', methods: ['GET', 'POST'])]
    public function sortLesson(
        Course $course,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager,
        TranslatorInterface $trans,
        LessonRepository $lessonRepository,
        AdminUrlGenerator $adminUrlGenerator,
        CourseLinker $courseLinker
    ) {
        if ('GET' === $request->getMethod()) {
            return $this->render('admin/course/courseForm.html.twig', [
                'course' => $course,
            ]);
        } else {
            $csrf = $request->request->get('_csrf_token', '');
            $sort = explode(',', $request->request->get('sort', ''));
            $button = $request->request->get('button-submit', '');

            $hasError = false;

            $token = new CsrfToken('admin_sort_lesson', $csrf);
            if (!$csrfTokenManager->isTokenValid($token)) {
                $hasError = true;
                $this->addFlash('sortError', $trans->trans('csrf', domain: 'admin'));
            }

            foreach ($sort as $id) {
                if (!$lessonRepository->lessonExists(Uuid::fromRfc4122($id))) {
                    $hasError = true;
                    $this->addFlash('sortError', $trans->trans('id', domain: 'admin'));
                }
            }

            if ($hasError) {
                return $this->render('admin/course/courseForm.html.twig', [
                    'course' => $course,
                ]);
            }

            $courseLinker->link($sort);

            if ('saveAndContinue' === $button) {
                $targetUrl = $adminUrlGenerator
                    ->setRoute('admin_course_sort', ['id' => $course->getId()->toRfc4122()])
                    ->generateUrl();
            } else {
                $targetUrl = $adminUrlGenerator
                    ->setController(CourseCrudController::class)
                    ->setAction(Crud::PAGE_INDEX)
                    ->generateUrl();
            }

            return $this->redirect($targetUrl);
        }
    }
}