<?php

namespace App\Controller\Api\Rest;

use App\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rest')]
class UserController extends AbstractController
{
    #[Route('/users', 'api_get_users', methods: ['GET'])]
    public function getUsers()
    {
        throw new RequestException('New error', 4001);

        return $this->json(['Hello' => 'World']);
    }
}
