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

use App\Entity\Lesson;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\LessonRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Lesson>
 *
 * @method Lesson|Proxy create(array|callable $attributes = [])
 * @method static Lesson|Proxy createOne(array $attributes = [])
 * @method static Lesson|Proxy find(object|array|mixed $criteria)
 * @method static Lesson|Proxy findOrCreate(array $attributes)
 * @method static Lesson|Proxy first(string $sortedField = 'id')
 * @method static Lesson|Proxy last(string $sortedField = 'id')
 * @method static Lesson|Proxy random(array $attributes = [])
 * @method static Lesson|Proxy randomOrCreate(array $attributes = [])
 * @method static LessonRepository|RepositoryProxy repository()
 * @method static Lesson[]|Proxy[] all()
 * @method static Lesson[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Lesson[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Lesson[]|Proxy[] findBy(array $attributes)
 * @method static Lesson[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Lesson[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Lesson> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Lesson> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Lesson> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Lesson> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Lesson> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Lesson> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Lesson> random(array $attributes = [])
 * @phpstan-method static Proxy<Lesson> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<LessonRepository> repository()
 * @phpstan-method static list<Proxy<Lesson>> all()
 * @phpstan-method static list<Proxy<Lesson>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Lesson>> createSequence(array|callable $sequence)
 * @phpstan-method static list<Proxy<Lesson>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Lesson>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Lesson>> randomSet(int $number, array $attributes = [])
 */
final class LessonFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->text(120),
            'description' => self::faker()->text(500),
            'content' => self::faker()->text(),
            'private' => false,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Lesson $lesson): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Lesson::class;
    }
}
