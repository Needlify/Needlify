<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Service\ParsedownFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_article', methods: ['GET'], options: ['expose' => true])]
    public function article(Article $article): Response
    {
        dd(ParsedownFactory::create()->text($article->getContent()));

        return $this->render('pages/article.html.twig', [
            'article' => $article,
        ]);
    }
}
