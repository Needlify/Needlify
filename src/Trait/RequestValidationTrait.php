<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Trait;

use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait RequestValidationTrait
{
    public function validateRequestQueryParams(Request $request, $constraints)
    {
        $validator = Validation::createValidator();
        $queryParams = $request->query->all();
        $errors = $validator->validate($queryParams, $constraints);

        if (count($errors) > 0) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, 'Invalid query param');
        }
    }
}
