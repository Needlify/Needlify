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

use App\Entity\Article;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\ArticleRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Article>
 *
 * @method static Article|Proxy                     createOne(array $attributes = [])
 * @method static Article[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Article[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Article|Proxy                     find(object|array|mixed $criteria)
 * @method static Article|Proxy                     findOrCreate(array $attributes)
 * @method static Article|Proxy                     first(string $sortedField = 'id')
 * @method static Article|Proxy                     last(string $sortedField = 'id')
 * @method static Article|Proxy                     random(array $attributes = [])
 * @method static Article|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Article[]|Proxy[]                 all()
 * @method static Article[]|Proxy[]                 findBy(array $attributes)
 * @method static Article[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Article[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ArticleRepository|RepositoryProxy repository()
 * @method        Article|Proxy                     create(array|callable $attributes = [])
 */
final class ArticleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->text(120),
            'description' => self::faker()->text(),
            'content' => self::faker()->text(),
            'thumbnail' => 'image.jpg',
            'license' => self::faker()->boolean(),
            'topic' => TopicFactory::random(),
            'tags' => TagFactory::randomSet(5),
            'author' => UserFactory::random(),
            'private' => true,
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Article::class;
    }
}
