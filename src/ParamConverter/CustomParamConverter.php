<?php

namespace App\ParamConverter;

use App\Exception\RessourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class CustomParamConverter extends DoctrineParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            return parent::apply($request, $configuration);
        } catch (\Throwable $exception) {
            if ($exception instanceof NotFoundHttpException) {
                throw new RessourceNotFoundException([$configuration->getName(), $configuration->getClass()]);
            }

            return false;
        }
    }
}
