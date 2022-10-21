<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;

class FormValidation
{
    public function validateFormParams(Request $request, $constraints)
    {
        $validator = Validation::createValidator();
        $queryParams = $request->request->all();

        return $validator->validate($queryParams, $constraints);
    }
}
