<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeedControllerTest extends WebTestCase
{
    /**
     * @dataProvider feedRoutesLoader
     */
    public function testFeedPages(string $route, array|string $methods): void
    {
        $client = static::createClient();

        if (is_array($methods)) {
            foreach ($methods as $method) {
                $crawler = $client->request($method, $route);
                $this->assertResponseIsSuccessful();
            }
        } else {
            $crawler = $client->request($methods, $route);
            $this->assertResponseIsSuccessful();
        }
    }

    public function feedRoutesLoader()
    {
        yield ['/', 'GET'];
    }
}
