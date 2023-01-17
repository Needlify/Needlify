<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model;

class ParamFetcher
{
    private array $parameters = [];

    public function all(): array
    {
        return $this->parameters;
    }

    public function get(string $key): mixed
    {
        return $this->parameters[$key];
    }

    public function set(string $key, mixed $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    public function values(): array
    {
        return array_values($this->parameters);
    }
}
