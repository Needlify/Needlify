<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolver;

use App\Attribut\QueryParam;
use App\Enum\QueryParamType;
use App\Service\ParamFetcher;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QueryParamArgumentResolver implements ValueResolverInterface
{
    private const IGNORE = [];

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (ParamFetcher::class !== $argumentType) {
            return self::IGNORE;
        }

        // Retrieve controller and method from the request
        $route = $request->attributes->get('_controller');
        [$controller, $method] = explode('::', $route);

        // Create a reflection of the controller
        $rController = new \ReflectionClass($controller);
        $rMethod = $rController->getMethod($method);

        // Get attributs of type QueryParam
        $rAttributs = $rMethod->getAttributes(QueryParam::class);

        if (empty($rAttributs)) {
            return self::IGNORE;
        }

        $fetcher = new ParamFetcher();

        foreach ($rAttributs as $attribut) {
            $queryParamParameters = $this->getQueryParamParameters($attribut);
            $queryParamName = $queryParamParameters->getName();
            if ($request->query->has($queryParamName)) {
                $fetcher->set($queryParamName, $request->query->get($queryParamName));
            } else {
                $fetcher->set($queryParamName, $queryParamParameters->getDefault());
            }
            $this->validateParamFetcher($fetcher, $queryParamParameters);
        }

        return [$fetcher];
    }

    private function getQueryParamParameters(\ReflectionAttribute $attribut): QueryParam
    {
        $queryParamProperties = (new \ReflectionClass(QueryParam::class))->getProperties();
        foreach ($queryParamProperties as $key => $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $attribut->getArguments())) {
                $values[$propertyName] = $attribut->getArguments()[$propertyName];
            } elseif (array_key_exists($key, $attribut->getArguments())) {
                $values[$propertyName] = $attribut->getArguments()[$key];
            } else {
                $values[$propertyName] = $property->getDefaultValue();
            }
        }

        return new QueryParam(...$values);
    }

    /**
     * @throws BadRequestHttpException
     */
    private function validateParamFetcher(ParamFetcher $fetcher, QueryParam $queryParam): void
    {
        dd($fetcher, $queryParam);
        $value = $fetcher->get($queryParam->getName());

        switch ($queryParam->getType()) {
            case QueryParamType::INTEGER:
                // $validatedType = filter_var();
                // https://www.php.net/manual/en/filter.filters.validate.php
                break;
        }

        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
    }
}
