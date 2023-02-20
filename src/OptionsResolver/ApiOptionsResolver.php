<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\OptionsResolver;

use App\Attribut\QueryParam;
use App\Enum\QueryParamType;
use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiOptionsResolver extends OptionsResolver
{
    public function addOption(QueryParam $queryParam): self
    {
        $name = $queryParam->getName();

        // On annonce que ce champ existe
        $this->setDefined($name);

        // On le rend obligatoire ou pas
        if (!$queryParam->getOptional()) {
            $this->setRequired($name);
        }

        // On ajoute la valeur par défaut
        if (null !== $queryParam->getDefault()) {
            $this->setDefault($name, $queryParam->getDefault());
        }

        $this->setOptionType($queryParam);

        // Validate constraints
        // ! This section must be after the type normalizer one because the code below is based on the value returned by the previous normalizer
        if (!empty($queryParam->getRequirements())) {
            $this->validateRequirements($queryParam);
        }

        return $this;
    }

    private function setOptionType(QueryParam $queryParam)
    {
        $fieldName = $queryParam->getName();

        switch ($queryParam->getType()) {
            case QueryParamType::INTEGER:
                $this->setIntegerType($fieldName);
                break;

            case QueryParamType::FLOAT:
                $this->setFloatType($fieldName);
                break;

            case QueryParamType::UUID:
                $this->setUuidType($fieldName);
                break;

            case QueryParamType::STRING:
                $this->setStringType($fieldName);
                break;

            default:
                throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM, 'Invalid type for $%s parameter (available types: %s)', [$queryParam->getName(), implode(', ', QueryParamType::values())]);
        }
    }

    private function validateRequirements(QueryParam $queryParam)
    {
        $this->addNormalizer($queryParam->getName(), function (Options $options, $value) use ($queryParam) {
            $validator = Validation::createValidator();
            $violations = $validator->validate($value, $queryParam->getRequirements());

            if (0 === count($violations)) {
                return $value;
            } else {
                throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::INVALID_QUERY_PARAM_REQUIREMENT, 'Invalid $%s parameter', [$queryParam->getName()]);
            }
        });
    }

    private function setIntegerType(string $fieldName)
    {
        $this->setAllowedTypes($fieldName, 'numeric');
        $this->addAllowedValues($fieldName, function ($value) {
            $validatedValue = filter_var($value, FILTER_VALIDATE_INT, [
                // 'options' => ['min_range' => 100, 'max_range' => 500],
                'flags' => FILTER_NULL_ON_FAILURE,
            ]);

            return null !== $validatedValue;
        });
        $this->addNormalizer($fieldName, fn (Options $options, $value) => (int) $value);
    }

    private function setFloatType(string $fieldName)
    {
        $this->setAllowedTypes($fieldName, 'numeric');
        $this->addAllowedValues($fieldName, function ($value) {
            $validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT, [
                // 'options' => ['min_range' => 100, 'max_range' => 500],
                'flags' => FILTER_NULL_ON_FAILURE,
            ]);

            return null !== $validatedValue;
        });
        $this->addNormalizer($fieldName, fn (Options $options, $value) => (float) $value);
    }

    private function setUuidType(string $fieldName)
    {
        $this->setAllowedTypes($fieldName, 'string');
        $this->addAllowedValues($fieldName, function ($value) {
            return Uuid::isValid($value);
        });
        $this->addNormalizer($fieldName, fn (Options $options, $value) => Uuid::fromString($value));
    }

    private function setStringType(string $fieldName)
    {
        $this->setAllowedTypes($fieldName, 'string');
    }
}
