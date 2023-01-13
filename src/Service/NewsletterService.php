<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Model\NewsletterPage;
use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Model\NewsletterContent;
use App\Entity\NewsletterAccount;
use Symfony\Component\Mime\Email;
use App\Exception\ExceptionFactory;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpClient\HttpClient;
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
            ->subject('Newsletter ccount confirmation')
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

    public function isPublishRequestValid(Request $request): bool
    {
        $result = true;

        $authorizationRaw = $request->headers->get('authorization');
        $authorization = str_replace('Basic ', '', $authorizationRaw);
        $user = $request->headers->get('php-auth-user');
        $password = $request->headers->get('php-auth-pw');

        if (!$authorization || !$user || !$password ||
           base64_decode($authorization) !== "{$user}:{$password}" ||
           $user !== $_ENV['NEWSLETTER_AUTH_USER'] || $password !== $_ENV['NEWSLETTER_AUTH_PASS']
        ) {
            $result = false;
        }

        return $result;
    }

    public function getTodaysNewsletterInfos(): NewsletterPage
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')));
        $client = HttpClient::create();
        $request = $client->request('POST', "https://api.notion.com/v1/databases/{$_ENV['NOTION_NEWSLETTER_DATABASE_ID']}/query", [
           'headers' => [
              'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
              'Notion-Version' => '2021-08-16',
              'Content-Type' => 'application/json',
           ],
           'json' => [
              'filter' => [
                 'and' => [
                    [
                       'property' => 'Date',
                       'date' => [
                          'equals' => $date->format('Y-m-d'),
                       ],
                    ],
                 ],
              ],
           ],
        ]);

        $pageInfoArray = $request->toArray();

        $pageInfo = new NewsletterPage();
        $pageInfo->setCanBePublished(!empty($pageInfoArray['results']));

        if ($pageInfo->getCanBePublished()) {
            $currentPage = $pageInfoArray['results'][0];
            $pageInfo
                ->setTitle($currentPage['properties']['Content']['title'][0]['plain_text'])
                ->setEmoji($currentPage['icon']['emoji'])
                ->setPageId($currentPage['id'])
                ->setDate($date)
                ->setNewsletterUrl($currentPage['url']);
        }

        return $pageInfo;
    }

   public function getTodaysNewsletterContent(NewsletterPage $pageInfo): NewsletterContent
   {
       $client = HttpClient::create();
       $request = $client->request('GET', "https://api.notion.com/v1/blocks/{$pageInfo->getPageId()}/children", [
          'headers' => [
              'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
              'Notion-Version' => '2021-08-16',
              'Content-Type' => 'application/json',
          ],
       ]);

       $pageContentArray = $request->toArray();

       $content = $pageContentArray['results'];

       $allowedKeys = ['type', 'paragraph', 'heading_1', 'heading_2', 'heading_3'];
       foreach ($content as $key => $block) {
           $content[$key] = array_intersect_key($block, array_flip($allowedKeys));
       }

       $pageContent = new NewsletterContent();
       $pageContent
            ->createFromNewsletterPage($pageInfo)
            ->setContent($content);

       return $pageContent;
   }

   public function updateNotionPageStatus(NewsletterPage $newsletterInfos)
   {
       $pageId = $newsletterInfos->getPageId();

       $client = HttpClient::create();
       $request = $client->request('PATCH', "https://api.notion.com/v1/pages/{$pageId}", [
          'headers' => [
              'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
              'Notion-Version' => '2021-08-16',
              'Content-Type' => 'application/json',
          ],
          'json' => [
             'properties' => [
                'Status' => [
                   'select' => [
                      'name' => 'Published',
                   ],
                ],
             ],
          ],
       ]);
   }
}
