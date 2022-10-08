<?php

namespace App\Controller\Api;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use App\Service\ClassifierType;
use App\Service\RequestValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/api/rest')]
class PublicationController extends AbstractController
{
    private PublicationRepository $publicationRepository;
    private RequestValidation $requestValidation;

    public function __construct(PublicationRepository $publicationRepository, RequestValidation $requestValidation)
    {
        $this->publicationRepository = $publicationRepository;
        $this->requestValidation = $requestValidation;
    }

    #[Route('/publications', 'api_get_publications', methods: ['GET'], options: ['expose' => true])]
    public function getUsersAction(Request $request): JsonResponse
    {
        $constraints = new Assert\Collection([
            'offset' => new Assert\Optional([
                new Assert\Type('numeric'),
                new Assert\PositiveOrZero(),
            ]),
            'selector' => new Assert\Optional([
                new Assert\Type('string'),
                new Assert\Choice(ClassifierType::values()),
            ]),
            'id' => new Assert\Optional([
                new Assert\Type('string'),
                new Assert\Uuid(),
            ]),
        ]);

        $this->requestValidation->validateRequestQueryParams($request, $constraints);

        $offset = $request->query->get('offset') ?? 0;
        $selector = $request->query->get('selector') ?? null;
        $id = $request->query->get('id') ?? null;

        return $this->json($this->publicationRepository->findAllWithPagination($offset, $selector, $id), context: ['groups' => 'thread:extend']);
    }

    #[Route('/publications/{id}', 'api_get_publication', methods: ['GET'], options: ['expose' => true])]
    public function getUserAction(Publication $publication): JsonResponse
    {
        return $this->json($publication, context: ['groups' => 'thread:extend']);
    }
}
