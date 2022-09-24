<?php

namespace App\Entity;

use App\Entity\Interface\PublicationInterface;
use App\Repository\ArticleRepository;
use App\Service\PublicationType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article extends Publication implements PublicationInterface
{
    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Assert\NotBlank(message: "Le titre d'un article ne pas être vide")]
    #[Assert\Length(max: 120, maxMessage: "Le titre d'un article ne peut pas dépasser {{ limite }} caractères")]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 500)]
    #[Assert\NotBlank(message: "La description de l'article ne pas être vide")]
    #[Assert\Length(max: 500, maxMessage: "La description d'un article ne peut pas dépasser {{ limite }} caractères")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La contenu de l'article ne pas être vide")]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\PositiveOrZero(message: 'Le nombre de vues doit être positif ou null')]
    private ?int $views = null;

    #[ORM\Column(type: Types::STRING, length: 130)]
    #[Assert\NotBlank(message: "Le slug de l'article ne pas être vide")]
    #[Assert\Length(max: 130, maxMessage: "Le slug d'un article ne peut pas dépasser {{ limite }} caractères")]
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
        $this->slug = $slugger->slug($this->title) . '-' . hash('adler32', $this->title);
    }

    public function getType(): string
    {
        return PublicationType::ARTICLE->value;
    }

    public function getPreview(): string
    {
        return $this->getDescription();
    }
}
