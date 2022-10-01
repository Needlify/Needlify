<?php

namespace App\Controller\Api;

use App\Entity\Thread;
use App\ParamConverter\CustomParamConverter;
use App\Repository\ThreadRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        $offset = $request->query->get('offset');
        sleep(2);

        return $this->json($threadRepository->findAllWithPagination($offset), context: ['groups' => 'thread:extend']);
    }

    #[Route('/threads/{id}', 'api_get_thread', methods: ['GET'])]
    #[ParamConverter(data: 'id', class: Thread::class, converter: CustomParamConverter::class)]
    public function getUserAction(Thread $thread): JsonResponse
    {
        return $this->json($thread, context: ['groups' => 'thread:extend']);
    }
}
