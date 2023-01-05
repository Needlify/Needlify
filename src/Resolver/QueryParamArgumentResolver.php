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
use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\Validator\Validation;
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

            $this->validateQueryParamParameters($queryParamParameters);

            $queryParamValue = $this->getQueryParamValue($request, $queryParamName, $queryParamParameters);
            $formattedValue = $this->validateParamFetcher($queryParamValue, $queryParamParameters);

            $fetcher->set($queryParamName, $formattedValue);
        }

        return [$fetcher];
    }

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

    /**
     * @throws BadRequestHttpException
     */
    private function validateQueryParamParameters(QueryParam $queryParam): void
    {
        if ($queryParam->getOptional() && null === $queryParam->getDefault()) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::MISSING_QUERY_PARAM_PARAMETER, 'If the parameter "optional" is set to true, you must also specify a default value');
        }
    }

    private function getQueryParamValue(Request $request, string $name, QueryParam $queryParam): ?string
    {
        if ($request->query->has($name)) {
            return $request->query->get($name);
        } else {
            return $queryParam->getDefault();
        }
    }

    private function validateRequirements(QueryParam $queryParam, ?string $value)
    {
        if (null !== $queryParam->getRequirements()) {
            if (is_string($queryParam->getRequirements()) && 0 === preg_match($queryParam->getRequirements(), $value)) {
                throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM_REQUIREMENT, 'Invalid $%s parameter', [$queryParam->getName()]);
            } elseif (is_array($queryParam->getRequirements())) {
                $validator = Validation::createValidator();
                $violations = $validator->validate($value, $queryParam->getRequirements());
                if (0 !== count($violations)) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM_REQUIREMENT, 'Invalid $%s parameter', [$queryParam->getName()]);
                }
            }
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    private function validateParamFetcher(?string $value, QueryParam $queryParam): mixed
    {
        switch ($queryParam->getType()) {
            case QueryParamType::INTEGER:
                $validatedValue = filter_var($value, FILTER_VALIDATE_INT, [
                    // 'options' => ['min_range' => 100, 'max_range' => 500],
                    'flags' => FILTER_NULL_ON_FAILURE,
                ]);

                $this->validateRequirements($queryParam, $value);

                if (null === $validatedValue) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be an integer', [$queryParam->getName()]);
                }

                return (int) $validatedValue;

            case QueryParamType::FLOAT:
                $validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT, [
                    // 'options' => ['min_range' => 100, 'max_range' => 500],
                    'flags' => FILTER_NULL_ON_FAILURE,
                ]);

                $this->validateRequirements($queryParam, $value);

                if (null === $validatedValue) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a float', [$queryParam->getName()]);
                }

                return (float) $validatedValue;

            case QueryParamType::STRING:
                if (!is_string($value)) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a string', [$queryParam->getName()]);
                }

                $this->validateRequirements($queryParam, $value);

                return $value;

            case QueryParamType::UUID:
                if (!is_string($value)) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a uuid', [$queryParam->getName()]);
                }

                if (!Uuid::isValid($value)) {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a uuid', [$queryParam->getName()]);
                }

                $this->validateRequirements($queryParam, $value);

                return Uuid::fromString($value);

            default:
                throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be of type integer, float, string or uuid', [$queryParam->getName()]);
        }
    }
}
