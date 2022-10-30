<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Topic;
use App\Entity\Article;
use App\Entity\Moodline;
use App\Service\Admin\OverviewService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private OverviewService $overviewService;

    public function __construct(OverviewService $overviewService)
    {
        $this->overviewService = $overviewService;
    }

    #[Route('/admin', name: 'admin_global')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'data' => $this->overviewService->getStats(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="../images/logo/logo.svg" style="height: 32px; display: block; margin: 0 auto;">')
            ->setFaviconPath('images/logo/favicon.ico')
            // ->setTranslationDomain('admin')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            // ->generateRelativeUrls()
            // ->setLocales([
            //     'en' => '🇬🇧 English',
            //     'fr' => '🇫🇷 Français',
            // ])
        ;
    }

    public function configureMenuItems(): iterable
    {
        // TODO Customiser le page du dashboard
        yield MenuItem::linkToDashboard('Overview', 'fas fa-chart-pie');
        yield MenuItem::linkToUrl('Home', 'fas fa-house', $this->generateUrl('app_home'));

        yield MenuItem::section('Publications');
        yield MenuItem::linkToCrud('Article', 'fas fa-book', Article::class);
        yield MenuItem::linkToCrud('Moodline', 'fas fa-bolt', Moodline::class);
        yield MenuItem::linkToCrud('Event', 'fas fa-bell', Event::class);

        yield MenuItem::section('Classifiers');
        yield MenuItem::linkToCrud('Tag', 'fas fa-hashtag', Tag::class);
        yield MenuItem::linkToCrud('Topic', 'fas fa-tag', Topic::class);

        yield MenuItem::section('Account');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }

    /**
     * @param User $user
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->displayUserName(false)
            ->displayUserAvatar(false)
            ->setMenuItems([
                // MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                // MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                // MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }
}
