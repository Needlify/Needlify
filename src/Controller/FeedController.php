<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeedController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ValidatorInterface $validator): Response
    {
        return $this->render('pages/feed.html.twig');
    }
}
