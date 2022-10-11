<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Symfony\Component\Validator\Validation;
use App\Exception\InvalidQueryParamException;
use Symfony\Component\HttpFoundation\Request;

class RequestValidation
{
    public function validateRequestQueryParams(Request $request, $constraints)
    {
        $validator = Validation::createValidator();
        $queryParams = $request->query->all();
        $errors = $validator->validate($queryParams, $constraints);

        if (count($errors) > 0) {
            throw new InvalidQueryParamException();
        }
    }
}
