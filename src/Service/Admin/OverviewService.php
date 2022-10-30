<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Admin;

use App\Entity\Tag;
use App\Entity\Event;
use App\Entity\Topic;
use App\Entity\Article;
use App\Entity\Moodline;
use Doctrine\ORM\EntityManagerInterface;

class OverviewService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getStats()
    {
        return [
            'article' => [
                'icon' => 'fas fa-book',
                'value' => $this->em->getRepository(Article::class)->countAll(),
            ],
            'moodline' => [
                'icon' => 'fas fa-bolt',
                'value' => $this->em->getRepository(Moodline::class)->countAll(),
            ],
            'tag' => [
                'icon' => 'fas fa-hashtag',
                'value' => $this->em->getRepository(Tag::class)->countAll(),
            ],
            'topic' => [
                'icon' => 'fas fa-tags',
                'value' => $this->em->getRepository(Topic::class)->countAll(),
            ],
            'event' => [
                'icon' => 'fas fa-bell',
                'value' => $this->em->getRepository(Event::class)->countAll(),
            ],
        ];
    }
}
