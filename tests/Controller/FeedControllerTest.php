<?php

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
