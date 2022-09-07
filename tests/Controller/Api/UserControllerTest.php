<?php

namespace App\Tests\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private function checkRoute(KernelBrowser $client, string $method, string $route)
    {
        $crawler = $client->request($method, $route);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * @dataProvider userSuccessRoutes
     */
    public function testUserRoute(string $route, array|string $methods): void
    {
        $client = static::createClient();

        if (is_array($methods)) {
            foreach ($methods as $method) {
                $this->checkRoute($client, $method, $route);
            }
        } else {
            $this->checkRoute($client, $methods, $route);
        }
    }

    public function userSuccessRoutes()
    {
        $kernel = self::bootKernel();

        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $em->getRepository(User::class)->findOneBy([]);

        return [
            ['/api/rest/users', 'GET'],
            ["/api/rest/users/{$user->getId()}", 'GET'],
        ];
    }
}
