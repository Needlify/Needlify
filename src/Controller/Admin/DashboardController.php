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
            ->setTitle('<img src="images/logo/logo.svg" style="height: 32px; display: block; margin: 0 auto;">')
            ->setFaviconPath('images/logo/favicon.ico')
            ->setTranslationDomain('admin')
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
        yield MenuItem::linkToDashboard('admin.sidebar.overview', 'fas fa-chart-pie');
        yield MenuItem::linkToUrl('admin.sidebar.home', 'fas fa-house', $this->generateUrl('app_home'));

        yield MenuItem::section('admin.sidebar.section.publication');
        yield MenuItem::linkToCrud('admin.sidebar.section.publication.articles', 'fas fa-book', Article::class);
        yield MenuItem::linkToCrud('admin.sidebar.section.publication.moodlines', 'fas fa-bolt', Moodline::class);
        yield MenuItem::linkToCrud('admin.sidebar.section.publication.events', 'fas fa-bell', Event::class);

        yield MenuItem::section('admin.sidebar.section.classifier');
        yield MenuItem::linkToCrud('admin.sidebar.section.classifier.tags', 'fas fa-hashtag', Tag::class);
        yield MenuItem::linkToCrud('admin.sidebar.section.classifier.topics', 'fas fa-tag', Topic::class);

        yield MenuItem::section('admin.sidebar.section.account');
        yield MenuItem::linkToCrud('admin.sidebar.section.account.users', 'fas fa-user', User::class);
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
                MenuItem::linkToLogout('admin.user_menu.logout', 'fa fa-sign-out'),
            ]);
    }
}
