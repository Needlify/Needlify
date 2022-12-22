<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Attribut\QueryParam;
use App\Enum\QueryParamType;
use App\Service\ParamFetcher;
use App\Repository\ThreadRepository;
use App\Trait\RequestValidationTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
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
    #[QueryParam('offset', type: QueryParamType::INTEGER, requirements: [new PositiveOrZero()])]
    public function getThreads(ParamFetcher $fetcher): JsonResponse
    {
        return $this->json(
            $this->threadRepository->findAllWithPagination($fetcher->get('offset')),
            context: ['groups' => 'thread:extend']
        );
    }
}
