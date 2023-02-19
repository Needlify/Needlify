<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolver;

use App\Model\ParamFetcher;
use App\Attribut\QueryParam;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use App\OptionsResolver\ApiOptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QueryParamArgumentResolver implements ValueResolverInterface
{
    private const IGNORE = [];

    public function __construct(
        private ApiOptionsResolver $apiOptionsResolver
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Check if the argument is of type ParamFetcher
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

        // If the method doesn't have any QueryParam attributs
        if (empty($rAttributs)) {
            return self::IGNORE;
        }

        foreach ($rAttributs as $attribut) {
            $queryParam = $this->getQueryParamParameters($attribut);
            $this->apiOptionsResolver->addOption($queryParam);
        }

        try {
            $validatedParams = $this->apiOptionsResolver->resolve($request->query->all());
        } catch (\Exception $e) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, $e->getMessage());
        }

        return [new ParamFetcher($validatedParams)];
    }

    /**
     * Create a QueryParam object from route attribut.
     */
    private function getQueryParamParameters(\ReflectionAttribute $attribut): QueryParam
    {
        $queryParamProperties = (new \ReflectionClass(QueryParam::class))->getProperties();
        $values = [];
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
}
