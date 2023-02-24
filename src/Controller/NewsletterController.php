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

use App\Exception\ExceptionCode;
use App\Entity\NewsletterAccount;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Newsletter\NewsletterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NewsletterAccountRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Newsletter\NewsletterRequestService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[Route('/newsletter')]
class NewsletterController extends AbstractController
{
    public function __construct(
        private NewsletterService $newsletterService,
        private NewsletterRequestService $newsletterRequestService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/register', methods: ['GET', 'POST'], name: 'app_newsletter_register')]
    #[Route('/registration', methods: ['GET', 'POST'], name: 'app_newsletter_registration')]
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
            $account->setEmail($request->request->get('email', ''));

            $this->em->persist($account);
            $this->em->flush();

            return $this->redirectToRoute('app_newsletter_verification_pending', [
                'token' => $account->getToken(),
            ]);
        }
    }

    #[Route('/verification/pending', methods: ['GET'], name: 'app_newsletter_verification_pending')]
    public function accountVerificationPending(Request $request)
    {
        $token = $request->query->get('token', '');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        if ($account->getIsVerified()) {
            return $this->redirectToRoute('app_newsletter_verification_completed', [
                'token' => $token,
            ]);
        }

        // Envoye du nouvel mail si possible
        if ($account->canRetryConfirmation()) {
            $this->newsletterService->sendVerificationMail($account);
        } else {
            $this->addFlash('error', $this->translator->trans('pending.error', [], 'newsletter'));
        }

        return $this->render('newsletter/pending.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/verification/confirm', methods: ['GET'], name: 'app_newsletter_verification_confirm')]
    public function accountVerificationConfirmation(Request $request)
    {
        $token = $request->query->get('token', '');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        if (!$account->getIsVerified()) {
            $account->setIsVerified(true)
                ->setVerifiedAt();

            $this->em->persist($account);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_newsletter_verification_completed', [
            'token' => $token,
        ]);
    }

    #[Route('/verification/completed', methods: ['GET'], name: 'app_newsletter_verification_completed')]
    public function accountVerificationCompleted(Request $request)
    {
        $token = $request->query->get('token', '');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        if (!$account->getIsVerified()) {
            return $this->redirectToRoute('app_newsletter_verification_pending', [
                'token' => $token,
            ]);
        }

        return $this->render('newsletter/completed.html.twig');
    }

    #[Route('/unsubscribe', methods: ['GET'], name: 'app_newsletter_unsubscribe')]
    public function unsubscribe(Request $request, NewsletterAccountRepository $newsletterAccountRepository)
    {
        $token = $request->query->get('token', '');
        $account = $this->newsletterService->verifyTokenAndGetAccount($token);

        $newsletterAccountRepository->remove($account, true);

        return $this->render('newsletter/unsubscribed.html.twig');
    }

    #[Route('/publish', methods: ['GET'], name: 'app_newsletter_publish')]
    public function publish(Request $request)
    {
        if (!$this->newsletterService->isPublishRequestValid($request)) {
            throw ExceptionFactory::throw(AccessDeniedHttpException::class, ExceptionCode::INVALID_NEWSLETTER_CREDENTIALS, 'Invalid newsletter credentials');
        }

        $pageInfo = $this->newsletterRequestService->getTodaysNewsletterInfos();

        if ($pageInfo->getCanBePublished()) {
            $newsletterContent = $this->newsletterRequestService->getTodaysNewsletterContent($pageInfo);

            $this->newsletterService->publishNewsletter($newsletterContent);
        }

        return new Response();
    }
}
