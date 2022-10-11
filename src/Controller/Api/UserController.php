<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
