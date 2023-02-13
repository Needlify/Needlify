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

class NewsletterContent
{
    private NewsletterPage $newsletterPage;
    private ?array $content = null;

    public function getNewsletterPage(): NewsletterPage
    {
        return $this->newsletterPage;
    }

    public function setNewsletterPage(NewsletterPage $newsletterPage): self
    {
        $this->newsletterPage = $newsletterPage;

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
