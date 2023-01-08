<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\NewsletterAccount;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    public function __construct(
        private NewsletterService $newsletterService,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/newsletter/register', methods: ['GET', 'POST'], name: 'app_newsletter_register')]
    public function register(Request $request)
    {
        if ('GET' === $request->getMethod()) {
            return $this->render('newsletter/register.html.twig');
        } else {
            $hasError = $this->newsletterService->validateRegistrationRequestParam($request);

            if ($hasError) {
                return $this->render('newsletter/register.html.twig');
            }

            $account = new NewsletterAccount();
            $account->setEmail($request->request->get('email'));

            $this->em->persist($account);
            $this->em->flush();

            return $this->render('newsletter/register.html.twig');
        }
    }
}
