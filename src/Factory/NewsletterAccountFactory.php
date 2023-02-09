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

use Zenstruck\Foundry\Proxy;
use App\Entity\NewsletterAccount;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\RepositoryProxy;
use App\Repository\NewsletterAccountRepository;

/**
 * @extends ModelFactory<NewsletterAccount>
 *
 * @method NewsletterAccount|Proxy create(array|callable $attributes = [])
 * @method static NewsletterAccount|Proxy createOne(array $attributes = [])
 * @method static NewsletterAccount|Proxy find(object|array|mixed $criteria)
 * @method static NewsletterAccount|Proxy findOrCreate(array $attributes)
 * @method static NewsletterAccount|Proxy first(string $sortedField = 'id')
 * @method static NewsletterAccount|Proxy last(string $sortedField = 'id')
 * @method static NewsletterAccount|Proxy random(array $attributes = [])
 * @method static NewsletterAccount|Proxy randomOrCreate(array $attributes = [])
 * @method static NewsletterAccountRepository|RepositoryProxy repository()
 * @method static NewsletterAccount[]|Proxy[] all()
 * @method static NewsletterAccount[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static NewsletterAccount[]|Proxy[] createSequence(array|callable $sequence)
 * @method static NewsletterAccount[]|Proxy[] findBy(array $attributes)
 * @method static NewsletterAccount[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static NewsletterAccount[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<NewsletterAccount> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<NewsletterAccount> createOne(array $attributes = [])
 * @phpstan-method static Proxy<NewsletterAccount> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<NewsletterAccount> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<NewsletterAccount> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<NewsletterAccount> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<NewsletterAccount> random(array $attributes = [])
 * @phpstan-method static Proxy<NewsletterAccount> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<NewsletterAccountRepository> repository()
 * @phpstan-method static list<Proxy<NewsletterAccount>> all()
 * @phpstan-method static list<Proxy<NewsletterAccount>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<NewsletterAccount>> createSequence(array|callable $sequence)
 * @phpstan-method static list<Proxy<NewsletterAccount>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<NewsletterAccount>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<NewsletterAccount>> randomSet(int $number, array $attributes = [])
 */
final class NewsletterAccountFactory extends ModelFactory
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
            'email' => self::faker()->email(),
            'isEnabled' => true,
            'isVerified' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (NewsletterAccount $newsletterAccount) {
                if ($newsletterAccount->getIsVerified()) {
                    $newsletterAccount->setVerifiedAt();
                }
            })
        ;
    }

    protected static function getClass(): string
    {
        return NewsletterAccount::class;
    }
}
