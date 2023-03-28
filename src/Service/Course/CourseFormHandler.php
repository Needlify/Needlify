<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Course;

use Symfony\Component\Uid\Uuid;
use App\Repository\LessonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CourseFormHandler
{
    public function __construct(
        private TranslatorInterface $trans,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private LessonRepository $lessonRepository
    ) {
    }

    public function validateFormRequest(Request $request): array
    {
        $csrf = $request->request->get('_csrf_token', '');
        $rawSort = $request->request->get('sort', '');
        $button = $request->request->get('button-submit', '');

        $errors = [];

        $token = new CsrfToken('admin_sort_lesson', $csrf);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $errors[] = $this->trans->trans('admin.custom.course_form.errors.csrf', domain: 'admin');
        }

        if (preg_match("/^([a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12})(,([a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}))*$/", $rawSort)) {
            $sort = explode(',', $rawSort);
            foreach ($sort as $id) {
                if (!$this->lessonRepository->lessonExists(Uuid::fromRfc4122($id))) {
                    $errors[] = $this->trans->trans('admin.custom.course_form.errors.data', domain: 'admin');
                }
            }
        } else {
            $errors[] = $this->trans->trans('admin.custom.course_form.errors.data', domain: 'admin');
        }

        if (!in_array($button, [Action::SAVE_AND_CONTINUE, Action::SAVE_AND_RETURN])) {
            $errors[] = $this->trans->trans('admin.custom.course_form.errors.data', domain: 'admin');
        }

        return $errors;
    }
}
