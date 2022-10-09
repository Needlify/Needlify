<?php

namespace App\Service;

use App\Exception\InvalidQueryParamException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

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
