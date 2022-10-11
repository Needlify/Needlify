<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Topic;
use App\Service\ClassifierType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassifierController extends AbstractController
{
    #[Route('/topic/{slug}', name: 'app_topic', methods: ['GET'], options: ['expose' => true])]
    public function topicList(Topic $topic): Response
    {
        return $this->render('pages/classifier.html.twig', [
            'selector' => ClassifierType::TOPIC->value,
            'classifier' => $topic,
        ]);
    }

    #[Route('/tag/{slug}', name: 'app_tag', methods: ['GET'], options: ['expose' => true])]
    public function tagList(Tag $tag): Response
    {
        return $this->render('pages/classifier.html.twig', [
            'selector' => ClassifierType::TAG->value,
            'classifier' => $tag,
        ]);
    }
}
