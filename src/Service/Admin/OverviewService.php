<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Admin;

use App\Entity\Tag;
use App\Entity\Event;
use App\Entity\Topic;
use App\Entity\Banner;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Article;
use App\Entity\Moodline;
use App\Entity\NewsletterAccount;
use Doctrine\ORM\EntityManagerInterface;

class OverviewService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function getStats()
    {
        return [
            'article' => [
                'icon' => 'fas fa-book',
                'value' => $this->em->getRepository(Article::class)->countAll(),
                'translation' => 'admin.dashboard.cards.articles',
            ],
            'moodline' => [
                'icon' => 'fas fa-bolt',
                'value' => $this->em->getRepository(Moodline::class)->countAll(),
                'translation' => 'admin.dashboard.cards.moodlines',
            ],
            'tag' => [
                'icon' => 'fas fa-hashtag',
                'value' => $this->em->getRepository(Tag::class)->countAll(),
                'translation' => 'admin.dashboard.cards.tags',
            ],
            'topic' => [
                'icon' => 'fas fa-tags',
                'value' => $this->em->getRepository(Topic::class)->countAll(),
                'translation' => 'admin.dashboard.cards.topics',
            ],
            'event' => [
                'icon' => 'fas fa-bell',
                'value' => $this->em->getRepository(Event::class)->countAll(),
                'translation' => 'admin.dashboard.cards.events',
            ],
            'newsletter' => [
                'icon' => 'fas fa-newspaper',
                'value' => $this->em->getRepository(NewsletterAccount::class)->countAll(),
                'translation' => 'admin.dashboard.cards.newsletter_accounts',
            ],
            'banner' => [
                'icon' => 'fa fa-bullhorn',
                'value' => $this->em->getRepository(Banner::class)->countAll(),
                'translation' => 'admin.dashboard.cards.banners',
            ],
            'course' => [
                'icon' => 'fa fa-graduation-cap',
                'value' => $this->em->getRepository(Course::class)->countAll(),
                'translation' => 'admin.dashboard.cards.courses',
            ],
            'lesson' => [
                'icon' => 'fas fa-tasks',
                'value' => $this->em->getRepository(Lesson::class)->countAll(),
                'translation' => 'admin.dashboard.cards.lessons',
            ],
        ];
    }
}
