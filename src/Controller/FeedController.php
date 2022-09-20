<?php

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $posts = $this->em->getRepository(Publication::class)->findAll();

        return $this->render('pages/feed.html.twig', [
            'posts' => $posts,
        ]);
    }
}
