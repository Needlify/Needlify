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
use App\Service\Course\CourseLinker;
use App\Service\Course\CourseFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use App\Controller\Admin\Crud\CourseCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CourseAdminController extends AbstractController
{
    #[Route('/course/{id}/sort', 'admin_course_sort', methods: ['GET', 'POST'])]
    public function sortLesson(
        Course $course,
        Request $request,
        AdminUrlGenerator $adminUrlGenerator,
        CourseLinker $courseLinker,
        CourseFormHandler $courseFormHandler
    ) {
        if ('GET' === $request->getMethod()) {
            return $this->render('admin/course/course_sort_form.html.twig', [
                'course' => $course,
            ]);
        } else {
            $errors = $courseFormHandler->validateFormRequest($request);

            if (count($errors) > 0) {
                return $this->render('admin/course/course_sort_form.html.twig', [
                    'errors' => $errors,
                    'course' => $course,
                ]);
            }

            $order = explode(',', $request->request->get('sort'));
            $courseLinker->link($order);

            $button = $request->request->get('button-submit');
            if (Action::SAVE_AND_CONTINUE === $button) {
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
