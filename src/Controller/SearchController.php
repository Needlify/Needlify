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

use App\Enum\FeedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'], options: ['expose' => true])]
    public function index(Request $request): Response
    {
        $search = trim($request->query->get('q'));

        if (empty($search)) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pages/feed.html.twig', [
            'mode' => FeedType::SEARCH,
        ]);
    }
}
