<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DocumentRepository;
use App\Service\PopularityCalculatorService;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
abstract class Document extends Publication
{
    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Assert\NotBlank(message: 'document.title.not_blank')]
    #[Assert\Length(max: 120, maxMessage: 'document.title.length')]
    #[Groups(['thread:basic'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 500)]
    #[Assert\NotBlank(message: 'document.description.not_blank')]
    #[Assert\Length(max: 500, maxMessage: 'document.description.length')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'document.content.not_blank')]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\PositiveOrZero(message: 'document.views.positive_of_zero')]
    private ?int $views = 0;

    #[ORM\Column(type: Types::STRING, length: 134)]
    #[Assert\Length(max: 134, maxMessage: 'document.slug.length')]
    #[Groups(['thread:basic'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private ?bool $license = true;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): self
    {
        ++$this->views;

        return $this;
    }

    public function getPopularity(): float
    {
        return PopularityCalculatorService::calculatePopularity($this->views, $this->publishedAt);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    #[ORM\PrePersist]
    public function setSlug(): void
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug(
            u($this->title)->lower() . ' ' . uniqid()
        );
    }

    public function hasLicense(): ?bool
    {
        return $this->license;
    }

    public function setLicense(bool $license): self
    {
        $this->license = $license;

        return $this;
    }
}
