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
use Symfony\Component\Uid\Uuid;

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
                $queryParamValue = $request->query->get($queryParamName);
            } else {
                $queryParamValue = $queryParamParameters->getDefault();
            }

            $formattedValue = $this->validateParamFetcher($queryParamValue, $queryParamParameters);
            $fetcher->set($queryParamName, $formattedValue);
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
    private function validateParamFetcher(string $value, QueryParam $queryParam): string|int|float|bool|null|Uuid
    {
        switch ($queryParam->getType()) {
            case QueryParamType::INTEGER:
                $validatedValue = filter_var($value, FILTER_VALIDATE_INT);
                if(!$validatedValue) {
                    // TODO Modifier message d'erreur
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
                }

                // Possibilité de mettre positif ou negatif

                return (int) $validatedValue;

            case QueryParamType::FLOAT:
                $validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT);
                if(!$validatedValue) {
                    // TODO Modifier message d'erreur
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
                }

                return (float) $validatedValue;

            case QueryParamType::STRING:
                if(!is_string($value)) {
                    // TODO Modifier message d'erreur
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
                }

                return $value;


            case QueryParamType::UUID:
                if(!is_string($value)) {
                    // TODO Modifier message d'erreur
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
                }

                if(!Uuid::isValid($value)) {
                    // TODO Modifier message d'erreur
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'Ressource not found. Can not convert route parameter to retrieve', []);
                }

                return Uuid::fromString($value);
        }

    }
}
