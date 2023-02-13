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

use App\Entity\User;
use App\Exception\ExceptionCode;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route(path: '/login', name: 'auth_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error', $this->translator->trans('auth.error.invalid_credentials', domain: 'auth'));
        }

        return $this->render('auth/login.html.twig');
    }

    #[Route(path: '/logout', name: 'auth_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'auth_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator,
        UserRepository $userRepository,
    ): Response {
        if ($this->getUser() || $userRepository->atLeastOneUserExist()) {
            return $this->redirectToRoute('app_home');
        }

        if ('POST' === $request->getMethod()) {
            $token = $request->request->get('_csrf_token');

            if (!$this->isCsrfTokenValid('register', $token)) {
                throw ExceptionFactory::throw(InvalidCsrfTokenException::class, ExceptionCode::INVALID_CSRF_TOKEN, 'Invalid CSRF token');
            }

            $error = false;
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $passwordConfirm = $request->request->get('passwordConf');

            if ($password !== $passwordConfirm) {
                $error = true;
                $this->addFlash('error', $this->translator->trans('auth.error.passwords_match', domain: 'auth'));
            }

            $user = new User();

            $user->setEmail($email)
                 ->setUsername($username)
                 ->setRawPassword($password)
                 ->setPassword(
                     $userPasswordHasher->hashPassword(
                         $user,
                         $password
                     )
                 );

            $errors = $validator->validate($user, null, ['Default', 'auth:check:full']);

            if (count($errors) > 0) {
                $error = true;

                /** @var ConstraintViolation $validationError */
                foreach ($errors as $validationError) {
                    $this->addFlash('error', $validationError->getMessage());
                }
            }

            if ($error) {
                return $this->render('auth/register.html.twig');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('auth/register.html.twig');
    }
}
