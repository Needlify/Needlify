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
use App\Repository\PublicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest', format: 'json')]
class PublicationController extends AbstractController
{
    public function __construct(
        private PublicationRepository $publicationRepository
    ) {
    }

    #[Route('/publications', 'api_get_publications', methods: ['GET'], options: ['expose' => true])]
    #[QueryParam('page', type: QueryParamType::INTEGER, requirements: [new Positive()], optional: true, default: 1)]
    #[QueryParam('id', type: QueryParamType::UUID, requirements: [new Uuid()])]
    public function getPublications(ParamFetcher $fetcher): JsonResponse
    {
        $paginatedData = $this->publicationRepository->findAllWithPagination(
            $fetcher->get('page'),
            $fetcher->get('id')
        );

        return $this->json($paginatedData, context: ['groups' => 'thread:basic']);
    }
}
