<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Moodline;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\MoodlineRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Moodline>
 *
 * @method static Moodline|Proxy                     createOne(array $attributes = [])
 * @method static Moodline[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Moodline[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Moodline|Proxy                     find(object|array|mixed $criteria)
 * @method static Moodline|Proxy                     findOrCreate(array $attributes)
 * @method static Moodline|Proxy                     first(string $sortedField = 'id')
 * @method static Moodline|Proxy                     last(string $sortedField = 'id')
 * @method static Moodline|Proxy                     random(array $attributes = [])
 * @method static Moodline|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Moodline[]|Proxy[]                 all()
 * @method static Moodline[]|Proxy[]                 findBy(array $attributes)
 * @method static Moodline[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Moodline[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static MoodlineRepository|RepositoryProxy repository()
 * @method        Moodline|Proxy                     create(array|callable $attributes = [])
 */
final class MoodlineFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'content' => self::faker()->text(500),
            'topic' => TopicFactory::random(),
            'tags' => TagFactory::randomSet(5),
            'author' => UserFactory::random(),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Moodline::class;
    }
}
