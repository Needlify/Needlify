<?php

namespace App\Controller\Api;

use App\Entity\Thread;
use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rest')]
class ThreadController extends AbstractController
{
    #[Route('/threads', 'api_get_threads', methods: ['GET'])]
    public function getUsersAction(ThreadRepository $threadRepository, Request $request): JsonResponse
    {
        $offset = $request->query->get('offset') ?? 0;

        return $this->json($threadRepository->findAllWithPagination($offset), context: ['groups' => 'thread:extend']);
    }

    #[Route('/threads/{id}', 'api_get_thread', methods: ['GET'])]
    public function getUserAction(Thread $thread): JsonResponse
    {
        return $this->json($thread, context: ['groups' => 'thread:extend']);
    }
}
