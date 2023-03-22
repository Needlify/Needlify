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

use App\Entity\Banner;
use App\Enum\BannerType;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\BannerRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Banner>
 *
 * @method Banner|Proxy create(array|callable $attributes = [])
 * @method static Banner|Proxy createOne(array $attributes = [])
 * @method static Banner|Proxy find(object|array|mixed $criteria)
 * @method static Banner|Proxy findOrCreate(array $attributes)
 * @method static Banner|Proxy first(string $sortedField = 'id')
 * @method static Banner|Proxy last(string $sortedField = 'id')
 * @method static Banner|Proxy random(array $attributes = [])
 * @method static Banner|Proxy randomOrCreate(array $attributes = [])
 * @method static BannerRepository|RepositoryProxy repository()
 * @method static Banner[]|Proxy[] all()
 * @method static Banner[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Banner[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Banner[]|Proxy[] findBy(array $attributes)
 * @method static Banner[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Banner[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Banner> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Banner> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Banner> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Banner> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Banner> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Banner> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Banner> random(array $attributes = [])
 * @phpstan-method static Proxy<Banner> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<BannerRepository> repository()
 * @phpstan-method static list<Proxy<Banner>> all()
 * @phpstan-method static list<Proxy<Banner>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Banner>> createSequence(array|callable $sequence)
 * @phpstan-method static list<Proxy<Banner>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Banner>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Banner>> randomSet(int $number, array $attributes = [])
 */
final class BannerFactory extends ModelFactory
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
            'content' => self::faker()->text(1600),
            'priority' => self::faker()->numberBetween(-32768, 32768),
            'startedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'type' => self::faker()->randomElement(BannerType::cases()),
            'title' => self::faker()->optional()->text(255),
            'link' => self::faker()->optional()->url(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->beforeInstantiate(function (array $attributes): array {
                $attributes['endedAt'] = $attributes['startedAt']->modify('+1 month');

                return $attributes;
            })
        ;
    }

    protected static function getClass(): string
    {
        return Banner::class;
    }
}
