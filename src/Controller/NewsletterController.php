<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/register', methods: ['GET', 'POST'], name: 'app_newsletter_register')]
    public function register(Request $request)
    {
        if ('GET' === $request->getMethod()) {
            return $this->render('newsletter/register.html.twig');
        } else {
        }
    }
}
