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
use App\Repository\NewsletterAccountRepository;
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

            return $this->redirectToRoute('app_newsletter_verification_pending', [
                'token' => \base64_encode(\implode('::', [
                    $account->getEmail(),
                    $account->getId()->toRfc4122(),
                ])),
            ]);
        }
    }

    #[Route('/newsletter/verification/pending', methods: ['GET'], name: 'app_newsletter_verification_pending')]
    public function accountVerificationPending(Request $request)
    {
        $token = $request->query->get('token');

        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        // Envoye du nouveau mail si possible
        if ($account->canRetryConfirmation()) {
            $this->newsletterService->sendVerificationMail($account);
        } else {
            $this->addFlash('error', 'Attendez 3 minutes avant de réessayer');
        }

        return $this->render('newsletter/pending.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('newsletter/verification/completed', methods: ['GET'], name: 'app_newsletter_verification_completed')]
    public function accountVerificationCompleted(Request $request)
    {
        $token = $request->query->get('token');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        if (!$account->getIsVerified()) {
            $account->setIsVerified(true)
                ->setVerifiedAt();

            $this->em->persist($account);
            $this->em->flush();

            return $this->render('newsletter/completed.html.twig');
        } else {
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('newsletter/unsubscribe', methods: ['GET'], name: 'app_newsletter_unsubscribe')]
    public function unsubscribe(Request $request, NewsletterAccountRepository $newsletterAccountRepository)
    {
        $token = $request->query->get('token');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        $newsletterAccountRepository->remove($account, true);

        return $this->render('newsletter/unsubscribed.html.twig');
    }
}
