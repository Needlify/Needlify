<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Model\ParamFetcher;
use App\Attribut\QueryParam;
use App\Enum\QueryParamType;
use App\Repository\ThreadRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest', format: 'json')]
class ThreadController extends AbstractController
{
    public function __construct(
        private ThreadRepository $threadRepository
    ) {
    }

    #[Route('/threads', 'api_get_threads', methods: ['GET'], options: ['expose' => true])]
    #[QueryParam('page', type: QueryParamType::INTEGER, requirements: [new Positive()], optional: true, default: 1)]
    public function getThreads(ParamFetcher $fetcher): JsonResponse
    {
        $paginatedData = $this->threadRepository->findAllWithPagination($fetcher->get('page'));

        return $this->json($paginatedData, context: ['groups' => ['thread:basic']]);
    }
}
