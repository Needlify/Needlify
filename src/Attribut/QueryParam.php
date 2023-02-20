<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Attribut;

use App\Enum\QueryParamType;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class QueryParam
{
    private string $name;

    private QueryParamType $type = QueryParamType::STRING;

    /** @var Constraint[] */
    private array $requirements = [];

    private mixed $default = null;

    private bool $optional = false;

    public function __construct(
        string $name,
        QueryParamType $type = QueryParamType::STRING,
        array $requirements = [],
        mixed $default = null,
        bool $optional = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->requirements = $requirements;
        $this->default = $default;
        $this->optional = $optional;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): QueryParamType
    {
        return $this->type;
    }

    public function setType(QueryParamType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * @param Constraint[] $requirements
     */
    public function setRequirements(array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function setDefault(mixed $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): self
    {
        $this->optional = $optional;

        return $this;
    }
}
