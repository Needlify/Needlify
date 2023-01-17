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

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class QueryParam
{
    private string $name;
    private QueryParamType $type = QueryParamType::STRING;
    private string|array|null $requirements = null;
    private string|int|float|bool|null $default = null;
    private ?string $description = null;
    private bool $optional = false;

    public function __construct(
        string $name,
        QueryParamType $type = QueryParamType::STRING,
        string|array|null $requirements = null,
        string|int|float|bool|null $default = null,
        ?string $description = null,
        bool $optional = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->requirements = $requirements;
        $this->default = $default;
        $this->description = $description;
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

    public function getRequirements(): string|array|null
    {
        return $this->requirements;
    }

    public function setRequirements(string|array|null $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getDefault(): string|int|float|null|bool
    {
        return $this->default;
    }

    public function setDefault(string|int|float|null|bool $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
