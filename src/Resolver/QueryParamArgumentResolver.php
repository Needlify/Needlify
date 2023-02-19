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
use App\Enum\QueryParamType;
use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QueryParamArgumentResolver implements ValueResolverInterface
{
    private const IGNORE = [];

    private OptionsResolver $optionsResolver;

    public function __construct()
    {
        $this->optionsResolver = new OptionsResolver();
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

        if (empty($rAttributs)) {
            return self::IGNORE;
        }

        $fetcher = new ParamFetcher();

        foreach ($rAttributs as $attribut) {
            $queryParam = $this->getQueryParamParameters($attribut);
            $this->addOptionsResolverEntry($queryParam);

            // $queryParamName = $queryParamParameters->getName();

            // $this->validateQueryParamParameters($queryParamParameters);

            // $queryParamValue = $this->getQueryParamValue($request, $queryParamName, $queryParamParameters);
            // $formattedValue = $this->validateParamFetcher($queryParamValue, $queryParamParameters);

            // $fetcher->set($queryParamName, $formattedValue);
        }

        dd($this->optionsResolver->resolve($request->query->all()));

        return [$fetcher];
    }

    public function addOptionsResolverEntry(QueryParam $queryParam)
    {
        $name = $queryParam->getName();

        $this->optionsResolver->setDefined($name);

        if (!$queryParam->getOptional()) {
            $this->optionsResolver->setRequired($name);
        } else {
            $this->optionsResolver->setDefault($name, $queryParam->getDefault());
        }

        if (!empty($queryParam->getRequirements())) {
            $this->optionsResolver->setAllowedValues($name, Validation::createIsValidCallable(
                ...$queryParam->getRequirements()
            ));
        }

        $this->optionsResolver->setNormalizer($name, function (Options $options, $value) use ($queryParam) {
            switch ($queryParam->getType()) {
                case QueryParamType::STRING:
                    if (!is_string($value)) {
                        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a string', [$queryParam->getName()]);
                    }

                    return $value;

                case QueryParamType::INTEGER:
                    $validatedValue = filter_var($value, FILTER_VALIDATE_INT, [
                        // 'options' => ['min_range' => 100, 'max_range' => 500],
                        'flags' => FILTER_NULL_ON_FAILURE,
                    ]);

                    if (null === $validatedValue) {
                        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be an integer', [$queryParam->getName()]);
                    }

                    return (int) $validatedValue;

                case QueryParamType::FLOAT:
                    $validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT, [
                        // 'options' => ['min_range' => 100, 'max_range' => 500],
                        'flags' => FILTER_NULL_ON_FAILURE,
                    ]);

                    if (null === $validatedValue) {
                        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a float', [$queryParam->getName()]);
                    }

                    return (float) $validatedValue;

                case QueryParamType::UUID:
                    if (!is_string($value)) {
                        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a uuid', [$queryParam->getName()]);
                    }

                    if (!Uuid::isValid($value)) {
                        throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be a uuid', [$queryParam->getName()]);
                    }

                    return Uuid::fromString($value);

                default:
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be of type integer, float, string or uuid', [$queryParam->getName()]);
            }
        });
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

    // private function getQueryParamValue(Request $request, string $name, QueryParam $queryParam): ?string
    // {
    //     if ($request->query->has($name)) {
    //         return $request->query->get($name);
    //     } else {
    //         return $queryParam->getDefault();
    //     }
    // }
}
