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

use App\Entity\Course;
use Zenstruck\Foundry\Proxy;
use App\Enum\CourseDifficultyType;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\CourseRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Course>
 *
 * @method Course|Proxy create(array|callable $attributes = [])
 * @method static Course|Proxy createOne(array $attributes = [])
 * @method static Course|Proxy find(object|array|mixed $criteria)
 * @method static Course|Proxy findOrCreate(array $attributes)
 * @method static Course|Proxy first(string $sortedField = 'id')
 * @method static Course|Proxy last(string $sortedField = 'id')
 * @method static Course|Proxy random(array $attributes = [])
 * @method static Course|Proxy randomOrCreate(array $attributes = [])
 * @method static CourseRepository|RepositoryProxy repository()
 * @method static Course[]|Proxy[] all()
 * @method static Course[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Course[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Course[]|Proxy[] findBy(array $attributes)
 * @method static Course[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Course[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Course> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Course> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Course> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Course> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Course> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Course> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Course> random(array $attributes = [])
 * @phpstan-method static Proxy<Course> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<CourseRepository> repository()
 * @phpstan-method static list<Proxy<Course>> all()
 * @phpstan-method static list<Proxy<Course>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Course>> createSequence(array|callable $sequence)
 * @phpstan-method static list<Proxy<Course>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Course>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Course>> randomSet(int $number, array $attributes = [])
 */
final class CourseFactory extends ModelFactory
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
            'content' => self::faker()->text(),
            'description' => self::faker()->text(500),
            'difficulty' => self::faker()->randomElement(CourseDifficultyType::cases()),
            'license' => self::faker()->boolean(),
            'private' => false,
            'title' => self::faker()->text(120),
            'topic' => TopicFactory::random(),
            'tags' => TagFactory::randomSet(2),
            'author' => UserFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Course $course): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Course::class;
    }
}
