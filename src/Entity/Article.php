<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Service\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use App\Entity\Interface\ThreadInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article extends Publication implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Assert\NotBlank(message: "Le titre d'un article ne pas être vide")]
    #[Assert\Length(max: 120, maxMessage: "Le titre d'un article ne peut pas dépasser {{ limit }} caractères")]
    #[Groups(['thread:extend'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 500)]
    #[Assert\NotBlank(message: "La description de l'article ne pas être vide")]
    #[Assert\Length(max: 500, maxMessage: "La description d'un article ne peut pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La contenu de l'article ne pas être vide")]
    #[Groups(['thread:extend'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\PositiveOrZero(message: 'Le nombre de vues doit être positif ou null')]
    private int $views = 0;

    #[ORM\Column(type: Types::STRING, length: 134)]
    #[Assert\Length(max: 134, maxMessage: "Le slug d'un article ne peut pas dépasser {{ limit }} caractères")]
    #[Groups(['thread:extend'])]
    private ?string $slug = null;

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

    public function getViews(): ?int
    {
        return $this->views;
    }

    #[ORM\PrePersist]
    public function setViews(): void
    {
        $this->views = 0;
    }

    public function incrementViews(): self
    {
        ++$this->views;

        return $this;
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

    #[SerializedName('type')]
    #[Groups(['thread:extend'])]
    public function getType(): string
    {
        return ThreadType::ARTICLE->value;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:extend'])]
    public function getPreview(): string
    {
        return $this->getDescription();
    }

    public function __toString()
    {
        return $this->title;
    }
}
