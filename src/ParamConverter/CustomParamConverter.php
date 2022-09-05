<?php

namespace App\ParamConverter;

use App\Exception\RessourceNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomParamConverter extends DoctrineParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            return parent::apply($request, $configuration);
        } catch (\Throwable $exception) {
            if ($exception instanceof NotFoundHttpException) {
                // TODO Ajouter un Exception factory qui prend en param une exception et des params ainsi qu'un code d'erreur
                throw new RessourceNotFoundException(sprintf("%s with %s '%s' not found", $configuration->getClass(), $configuration->getName(), $request->get($configuration->getName())), 100, Response::HTTP_NOT_FOUND);
            }

            return false;
        }
    }
}
