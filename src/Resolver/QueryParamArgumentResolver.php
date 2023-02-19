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

        // If the method doesn't have any QueryParam attributs
        if (empty($rAttributs)) {
            return self::IGNORE;
        }

        foreach ($rAttributs as $attribut) {
            $queryParam = $this->getQueryParamParameters($attribut);
            $this->addOptionsResolverEntry($queryParam);
        }

        try {
            $validatedParams = $this->optionsResolver->resolve($request->query->all());
        } catch (\Exception $e) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, $e->getMessage());
        }

        return [new ParamFetcher($validatedParams)];
    }

    public function addOptionsResolverEntry(QueryParam $queryParam)
    {
        $name = $queryParam->getName();

        // On annonce que ce champ existe
        $this->optionsResolver->setDefined($name);

        // On le rend obligatoire ou pas
        if (!$queryParam->getOptional()) {
            $this->optionsResolver->setRequired($name);
        }

        // On ajoute la valeur par défaut
        if (null !== $queryParam->getDefault()) {
            $this->optionsResolver->setDefault($name, $queryParam->getDefault());
        }

        // Set allowed type
        // For each type, we specify the `string` type because every query param from the request is a string, it is not natively converted
        switch ($queryParam->getType()) {
            case QueryParamType::INTEGER:
                $this->optionsResolver->setAllowedTypes($name, 'string');
                $this->optionsResolver->addAllowedValues($name, function ($value) {
                    $validatedValue = filter_var($value, FILTER_VALIDATE_INT, [
                        // 'options' => ['min_range' => 100, 'max_range' => 500],
                        'flags' => FILTER_NULL_ON_FAILURE,
                    ]);

                    return null !== $validatedValue;
                });

                break;

            case QueryParamType::FLOAT:
                $this->optionsResolver->setAllowedTypes($name, 'string');
                $this->optionsResolver->addAllowedValues($name, function ($value) {
                    $validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT, [
                        // 'options' => ['min_range' => 100, 'max_range' => 500],
                        'flags' => FILTER_NULL_ON_FAILURE,
                    ]);

                    return null !== $validatedValue;
                });

                break;

            case QueryParamType::UUID:
                $this->optionsResolver->setAllowedTypes($name, 'string');
                $this->optionsResolver->addAllowedValues($name, function ($value) {
                    return Uuid::isValid($value);
                });

                break;

            case QueryParamType::STRING:
            default:
                $this->optionsResolver->setAllowedTypes($name, 'string');
                break;
        }

        // Normalize value
        $this->optionsResolver->addNormalizer($name, function (Options $options, $value) use ($queryParam) {
            switch ($queryParam->getType()) {
                case QueryParamType::STRING:
                    return $value;

                case QueryParamType::INTEGER:
                    return (int) $value;

                case QueryParamType::FLOAT:
                    return (float) $value;

                case QueryParamType::UUID:
                    return Uuid::fromString($value);

                default:
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, '$%s parameter must be of type integer, float, string or uuid', [$queryParam->getName()]);
            }
        });

        // Validate constraints
        // ! This section must be after the type normalizer one because the code below is based on the value returned by the previous normalizer
        if (!empty($queryParam->getRequirements())) {
            $this->optionsResolver->addNormalizer($name, function (Options $options, $value) use ($queryParam) {
                $validator = Validation::createValidator();
                $violations = $validator->validate($value, $queryParam->getRequirements());

                if (0 === count($violations)) {
                    return $value;
                } else {
                    throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM_REQUIREMENT, 'Invalid $%s parameter', [$queryParam->getName()]);
                }
            });
        }
    }

    /**
     * Create a QueryParam from route attribut.
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
