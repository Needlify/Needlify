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
use App\Repository\DocumentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest', format: 'json')]
class DocumentController extends AbstractController
{
    public function __construct(
        private DocumentRepository $documentRepository
    ) {
    }

    #[Route('/documents', 'api_get_documents', methods: ['GET'], options: ['expose' => true])]
    #[QueryParam('page', type: QueryParamType::INTEGER, requirements: [new Positive()], optional: true, default: 1)]
    #[QueryParam('search', type: QueryParamType::STRING, optional: true)]
    public function getPublications(ParamFetcher $fetcher): JsonResponse
    {
        $paginatedData = $this->documentRepository->findAllWithPagination(
            $fetcher->get('page'),
            $fetcher->get('search')
        );

        return $this->json($paginatedData, context: ['groups' => 'thread:basic']);
    }
}
