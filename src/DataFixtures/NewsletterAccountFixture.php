<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use Zenstruck\Foundry\Factory;
use Doctrine\Persistence\ObjectManager;
use App\Factory\NewsletterAccountFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NewsletterAccountFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        Factory::delayFlush(function () {
            NewsletterAccountFactory::createMany(2);
        });
    }
}
