<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rest')]
class UserController extends AbstractController
{
    #[Route('/users', 'api_get_users', methods: ['GET'])]
    public function getUsersAction(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), context: ['groups' => 'user:extend']);
    }

    #[Route('/users/{id}', 'api_get_user', methods: ['GET'])]
    public function getUserAction(User $user): JsonResponse
    {
        return $this->json($user, context: ['groups' => 'user:extend']);
    }
}
