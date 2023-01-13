<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model;

use DateTime;

class NewsletterPage
{
    private ?string $title = null;
    private ?string $emoji = null;
    private ?string $pageId = null;
    private ?string $newsletterUrl = null;
    private ?DateTime $date = null;
    private bool $canBePublished = false;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEmoji(): ?string
    {
        return $this->emoji;
    }

    public function setEmoji(?string $emoji): self
    {
        $this->emoji = $emoji;

        return $this;
    }

    public function getPageId(): ?string
    {
        return $this->pageId;
    }

    public function setPageId(?string $pageId): self
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getNewsletterUrl(): ?string
    {
        return $this->newsletterUrl;
    }

    public function setNewsletterUrl(?string $newsletterUrl): self
    {
        $this->newsletterUrl = $newsletterUrl;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCanBePublished(): bool
    {
        return $this->canBePublished;
    }

    public function setCanBePublished(bool $canBePublished): self
    {
        $this->canBePublished = $canBePublished;

        return $this;
    }
}
