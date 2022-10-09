<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_article', methods: ['GET'], options: ['expose' => true])]
    public function article(Article $article): Response
    {
        return $this->render('pages/article.html.twig', [
            'article' => $article,
        ]);
    }
}
