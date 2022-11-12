<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\ParamConverter;

use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class CustomParamConverter extends DoctrineParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            return parent::apply($request, $configuration);
        } catch (\Throwable $exception) {
            if ($exception instanceof NotFoundHttpException) {
                throw ExceptionFactory::throw(NotFoundHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve $%s of type %s', [$configuration->getName(), $configuration->getClass()]);
            }

            return false;
        }
    }
}
