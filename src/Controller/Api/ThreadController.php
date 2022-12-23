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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/rest')]
class ThreadController extends AbstractController
{
    private ThreadRepository $threadRepository;


    public function __construct(ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    #[Route('/threads', 'api_get_threads', methods: ['GET'], options: ['expose' => true])]
    #[QueryParam('offset', type: QueryParamType::INTEGER, requirements: [new PositiveOrZero()], optional: true, default: 0)]
    public function getThreads(ParamFetcher $fetcher): JsonResponse
    {
        $paginatedData = $this->threadRepository->findAllWithPagination($fetcher->get('offset'));


        return $this->json(
            $paginatedData->getSerializedData(),
            context: ['groups' => ['thread:extend']]
        );
    }
}
