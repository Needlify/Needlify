<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Admin;

use App\Entity\Article;
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
                'value' => 203,
            ],
            'tag' => [
                'icon' => 'fas fa-hashtag',
                'value' => 2356000000000000,
            ],
            'topic' => [
                'icon' => 'fas fa-tags',
                'value' => 5,
            ],
            'event' => [
                'icon' => 'fas fa-bell',
                'value' => 12,
            ],
        ];
    }
}
