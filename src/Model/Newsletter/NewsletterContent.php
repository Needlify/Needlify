<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model\Newsletter;

class NewsletterContent extends NewsletterPage
{
    private ?array $content = null;

    public function createFromNewsletterPage(NewsletterPage $newsletterPage): self
    {
        $this->setTitle($newsletterPage->getTitle());
        $this->setEmoji($newsletterPage->getEmoji());
        $this->setPageId($newsletterPage->getPageId());
        $this->setNewsletterUrl($newsletterPage->getNewsletterUrl());
        $this->setDate($newsletterPage->getDate());
        $this->setCanBePublished($newsletterPage->getCanBePublished());

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
