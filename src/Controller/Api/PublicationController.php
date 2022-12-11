<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Trait\RequestValidationTrait;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest')]
class PublicationController extends AbstractController
{
    use RequestValidationTrait;

    private PublicationRepository $publicationRepository;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    #[Route('/publications', 'api_get_publications', methods: ['GET'], options: ['expose' => true])]
    public function getUsersAction(Request $request): JsonResponse
    {
        // TODO: Refctor this method
        $constraints = new Assert\Collection([
            'offset' => new Assert\Optional([
                new Assert\Type('numeric'),
                new Assert\PositiveOrZero(),
            ]),
            'id' => new Assert\Optional([
                new Assert\Type('string'),
                new Assert\Uuid(),
            ]),
        ]);

        $this->validateRequestQueryParams($request, $constraints);

        $offset = $request->query->get('offset', 0);
        $id = $request->query->get('id', null);

        return $this->json($this->publicationRepository->findAllWithPagination($offset, $id), context: ['groups' => 'thread:extend']);
    }
}
