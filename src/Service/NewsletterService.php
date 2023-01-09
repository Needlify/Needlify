<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\NewsletterAccount;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class NewsletterService
{
    public function __construct(
        private RequestStack $requestStack,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private MailerInterface $mailer,
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
        $email = (new TemplatedEmail())
            ->from(Address::create('Needlify Noreply <noreply@needlify.com>'))
            ->to($account->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Newsletter ccount confirmation')
            ->textTemplate('email/newsletter/confirmation/confirmation.txt.twig')
            ->htmlTemplate('email/newsletter/confirmation/confirmation.html.twig')
            ->context([
                'user' => $account,
            ]);

        $this->mailer->send($email);
    }
}
