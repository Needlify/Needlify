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

use App\Repository\TagRepository;
use App\Repository\TopicRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', methods: ['GET'], format: 'xml')]
    public function sitemap(
        ArticleRepository $articleRepository,
        TopicRepository $topicRepository,
        TagRepository $tagRepository,
    ): Response {
        $urls = [];

        // Home
        $urls[] = [
            'loc' => $this->generateUrl(
                'app_home',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];

        // Tags
        foreach ($tagRepository->findAll() as $tag) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    'app_tag',
                    ['slug' => $tag->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => $tag->getLastUseAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        // Topics
        foreach ($topicRepository->findAll() as $topic) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    'app_topic',
                    ['slug' => $topic->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => $topic->getLastUseAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        // Articles
        foreach ($articleRepository->findBy(['private' => false]) as $article) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    'app_article',
                    ['slug' => $article->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => $article->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ];
        }

        // Legal
        $legalRoutes = ['app_legal', 'app_about', 'app_contact', 'app_terms', 'app_conduct', 'app_privacy'];
        foreach ($legalRoutes as $route) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    $route,
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'changefreq' => 'yearly',
                'priority' => '0.4',
            ];
        }

        // Newsletter
        $newsletterRoutes = ['app_newsletter_register', 'app_newsletter_registration'];
        foreach ($newsletterRoutes as $route) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    $route,
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'changefreq' => 'yearly',
                'priority' => '0.6',
            ];
        }

        return new Response(
            $this->renderView('sitemap/sitemap.xml.twig', ['urls' => $urls]),
            200,
            ['Content-Type' => 'application/xml']
        );
    }
}
