<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/post/{slug}', name: 'app_article', methods: ['GET'], options: ['expose' => true])]
    public function article(Article $article): Response
    {
        if ($article->isPrivate()) {
            throw ExceptionFactory::throw(NotFoundHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, 'This ressource is not accessible');
        }

        $article->incrementViews();

        $this->em->persist($article);
        $this->em->flush();

        return $this->render('pages/article.html.twig', [
            'article' => $article,
        ]);
    }
}
