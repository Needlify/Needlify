<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassifierController extends AbstractController
{
    #[Route('/topic/{slug}', name: 'app_topic', methods: ['GET'], options: ['expose' => true])]
    public function topicList(Topic $topic): Response
    {
        return $this->render('pages/classifier.html.twig', [
            'classifier' => $topic,
        ]);
    }

    #[Route('/tag/{slug}', name: 'app_tag', methods: ['GET'], options: ['expose' => true])]
    public function tagList(Tag $tag): Response
    {
        return $this->render('pages/classifier.html.twig', [
            'classifier' => $tag,
        ]);
    }
}
