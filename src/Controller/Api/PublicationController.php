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
use App\Repository\PublicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest')]
class PublicationController extends AbstractController
{

    private PublicationRepository $publicationRepository;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    #[Route('/publications', 'api_get_publications', methods: ['GET'], options: ['expose' => true])]
    #[QueryParam('offset', type: QueryParamType::INTEGER, requirements: [new PositiveOrZero()], optional: true, default: 0)]
    #[QueryParam('id', type: QueryParamType::UUID)]
    public function getPublications(ParamFetcher $fetcher): JsonResponse
    {
        return $this->json(
            $this->publicationRepository->findAllWithPagination(
                $fetcher->get('offset'),
                $fetcher->get('id')
            ),
            context: ['groups' => 'thread:extend']
        );
    }
}
