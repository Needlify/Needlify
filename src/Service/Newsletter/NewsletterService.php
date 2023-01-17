<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Newsletter;

use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Entity\NewsletterAccount;
use Symfony\Component\Mime\Email;
use App\Exception\ExceptionFactory;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Repository\NewsletterAccountRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NewsletterService
{
    public function __construct(
        private RequestStack $requestStack,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private NewsletterAccountRepository $newsletterAccountRepository
    ) {
    }

    public function validateRegistrationRequestParam(Request $request): bool
    {
        $hasError = false;
        $email = $request->request->get('email');
        $agree = $request->request->get('agree');
        $csrf = $request->request->get('_csrf_token');

        /** @var Session $session */
        $session = $this->requestStack->getSession();

        $token = new CsrfToken('newsletter', $csrf);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $hasError = true;
            $session->getFlashBag()->add('error', $this->translator->trans('newsletter_account.form.csrf', domain: 'validators'));
        }

        if (null === $agree || 'on' !== $agree) {
            $hasError = true;
            $session->getFlashBag()->add('error', $this->translator->trans('newsletter_account.form.agree', domain: 'validators'));
        }

        $account = new NewsletterAccount();
        $account->setEmail($email);

        $errors = $this->validator->validate($account);

        if (count($errors) > 0) {
            $hasError = true;

            /** @var ConstraintViolation $validationError */
            foreach ($errors as $validationError) {
                $session->getFlashBag()->add('error', $validationError->getMessage());
            }
        }

        return $hasError;
    }

    public function sendVerificationMail(NewsletterAccount $account)
    {
        if (!$account->getIsVerified()) {
            $email = (new TemplatedEmail())
            ->from(Address::create('Needlify Noreply <noreply@needlify.com>'))
            ->to($account->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Newsletter account confirmation')
            ->textTemplate('email/newsletter/confirmation/confirmation.txt.twig')
            ->htmlTemplate('email/newsletter/confirmation/confirmation.html.twig')
            ->context([
                'token' => $account->getToken(),
            ]);

            $this->mailer->send($email);

            $account->updateLastRetryAt();
            $this->em->persist($account);
            $this->em->flush();
        }
    }

    public function verifyTokenAndGetAccount(string $token): NewsletterAccount
    {
        if (null === $token) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::NEWSLETTER_REGISTRATION_TOKEN_MISSING, 'Missing token parameter');
        }

        $decodedToken = \base64_decode(\urldecode($token));
        [$email, $id] = explode('::', $decodedToken);

        $account = $this->newsletterAccountRepository->find(Uuid::fromRfc4122($id));

        if (!$account || $account->getEmail() !== $email) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::NEWSLETTER_REGISTRATION_INVALID_TOKEN, 'Invalid registration token');
        }

        return $account;
    }
}
