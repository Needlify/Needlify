<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Topic;
use Zenstruck\Foundry\Proxy;
use App\Repository\TopicRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Topic>
 *
 * @method static Topic|Proxy                     createOne(array $attributes = [])
 * @method static Topic[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Topic[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Topic|Proxy                     find(object|array|mixed $criteria)
 * @method static Topic|Proxy                     findOrCreate(array $attributes)
 * @method static Topic|Proxy                     first(string $sortedField = 'id')
 * @method static Topic|Proxy                     last(string $sortedField = 'id')
 * @method static Topic|Proxy                     random(array $attributes = [])
 * @method static Topic|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Topic[]|Proxy[]                 all()
 * @method static Topic[]|Proxy[]                 findBy(array $attributes)
 * @method static Topic[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Topic[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TopicRepository|RepositoryProxy repository()
 * @method        Topic|Proxy                     create(array|callable $attributes = [])
 */
final class TopicFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->unique()->words(asText: true),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Topic::class;
    }
}
