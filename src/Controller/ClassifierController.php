<?php

namespace App\Controller;

use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassifierController extends AbstractController
{
    #[Route('/topic/{slug}', name: 'app_classifier', methods: ['GET'])]
    public function index(Topic $topic): Response
    {
        return $this->render('pages/topic.html.twig', [
            'topic' => $topic,
        ]);
    }
}
