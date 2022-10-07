<?php

namespace App\Controller\Api;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rest')]
class PublicationController extends AbstractController
{
    #[Route('/publications', 'api_get_publications', methods: ['GET'])]
    public function getUsersAction(PublicationRepository $publicationRepository, Request $request): JsonResponse
    {
        $offset = $request->query->get('offset') ?? 0;
        $selector = $request->query->get('selector') ?? null;
        $id = $request->query->get('id') ?? null;

        return $this->json($publicationRepository->findAllWithPagination($offset, $selector, $id), context: ['groups' => 'thread:extend']);
    }

    #[Route('/publications/{id}', 'api_get_publication', methods: ['GET'])]
    public function getUserAction(Publication $publication): JsonResponse
    {
        return $this->json($publication, context: ['groups' => 'thread:extend']);
    }
}
