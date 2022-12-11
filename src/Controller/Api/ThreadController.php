<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Repository\ThreadRepository;
use App\Trait\RequestValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest')]
class ThreadController extends AbstractController
{
    use RequestValidationTrait;

    private ThreadRepository $threadRepository;

    public function __construct(ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    #[Route('/threads', 'api_get_threads', methods: ['GET'], options: ['expose' => true])]
    public function getUsersAction(Request $request): JsonResponse
    {
        // TODO: Refctor this method
        $constraints = new Assert\Collection([
            'offset' => new Assert\Optional([
                new Assert\Type('numeric'),
                new Assert\PositiveOrZero(),
            ]),
        ]);

        $this->validateRequestQueryParams($request, $constraints);

        $offset = $request->query->get('offset') ?? 0;

        return $this->json(
            $this->threadRepository->findAllWithPagination($offset),
            context: ['groups' => 'thread:extend']
        );
    }
}
