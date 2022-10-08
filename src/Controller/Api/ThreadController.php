<?php

namespace App\Controller\Api;

use App\Entity\Thread;
use App\Repository\ThreadRepository;
use App\Service\RequestValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/api/rest')]
class ThreadController extends AbstractController
{
    private ThreadRepository $threadRepository;

    private RequestValidation $requestValidation;

    public function __construct(ThreadRepository $threadRepository, RequestValidation $requestValidation)
    {
        $this->threadRepository = $threadRepository;
        $this->requestValidation = $requestValidation;
    }

    #[Route('/threads', 'api_get_threads', methods: ['GET'], options: ['expose' => true])]
    public function getUsersAction(Request $request): JsonResponse
    {
        $constraints = new Assert\Collection([
            'offset' => new Assert\Optional([
                new Assert\Type('numeric'),
                new Assert\PositiveOrZero(),
            ]),
        ]);
        $this->requestValidation->validateRequestQueryParams($request, $constraints);

        $offset = $request->query->get('offset') ?? 0;

        return $this->json(
            $this->threadRepository->findAllWithPagination($offset),
            context: ['groups' => 'thread:extend']
        );
    }

    #[Route('/threads/{id}', 'api_get_thread', methods: ['GET'], options: ['expose' => true])]
    public function getUserAction(Thread $thread): JsonResponse
    {
        return $this->json($thread, context: ['groups' => 'thread:extend']);
    }
}
