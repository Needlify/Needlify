<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Classifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassifierController extends AbstractController
{
    #[Route('/topic/{slug}', name: 'app_topic', methods: ['GET'], options: ['expose' => true])]
    #[Route('/tag/{slug}', name: 'app_tag', methods: ['GET'], options: ['expose' => true])]
    public function classifierList(Classifier $classifier): Response
    {
        return $this->render('pages/classifier.html.twig', [
            'classifier' => $classifier,
        ]);
    }
}
