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
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LessonRepository;
use App\Service\PopularityCalculatorService;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Lesson
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Course $course = null;

    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Assert\NotBlank(message: 'lesson.title.not_blank')]
    #[Assert\Length(max: 120, maxMessage: 'lesson.title.length')]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 500)]
    #[Assert\NotBlank(message: 'document.description.not_blank')]
    #[Assert\Length(max: 500, maxMessage: 'document.description.length')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'lesson.content.not_blank')]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\PositiveOrZero(message: 'lesson.views.positive_of_zero')]
    private ?int $views = 0;

    #[ORM\Column(type: Types::STRING, length: 134)]
    #[Assert\Length(max: 134, maxMessage: 'lesson.slug.length')]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    protected ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private ?bool $private = false;

    #[ORM\OneToOne(inversedBy: 'previous', targetEntity: self::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?self $next = null;

    #[ORM\OneToOne(mappedBy: 'next', targetEntity: self::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?self $previous = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

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

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    #[ORM\PrePersist]
    public function setPublishedAt(): void
    {
        // $this->publishedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->publishedAt = new \DateTimeImmutable('now');
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function autoUpdatedAt()
    {
        $this->refreshUpdatedAt();
    }

    public function refreshUpdatedAt()
    {
        // $this->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getAuthor()
    {
        return $this->course->getAuthor();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getNext(): ?self
    {
        return $this->next;
    }

    public function setNext(?self $next): self
    {
        $this->next = $next;

        return $this;
    }

    public function getPrevious(): ?self
    {
        return $this->previous;
    }

    public function setPrevious(?self $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    public function resetLink(): self
    {
        $this->previous = null;
        $this->next = null;

        return $this;
    }
}
