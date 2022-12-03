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
use App\Service\PopularityCalculatorService;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Article extends Publication implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Assert\NotBlank(message: 'article.title.not_blank')]
    #[Assert\Length(max: 120, maxMessage: 'article.title.length')]
    #[Groups(['thread:extend'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 500)]
    #[Assert\NotBlank(message: 'article.description.not_blank')]
    #[Assert\Length(max: 500, maxMessage: 'article.description.length')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'article.content.not_blank')]
    #[Groups(['thread:extend'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\PositiveOrZero(message: 'article.views.positive_of_zero')]
    private ?int $views = 0;

    #[ORM\Column(type: Types::STRING, length: 134)]
    #[Assert\Length(max: 134, maxMessage: 'article.slug.length')]
    #[Groups(['thread:extend'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotNull(message: 'article.thumbnail.not_null', groups: ['admin:form:edit'])]
    private ?string $thumbnail = null;

    #[Vich\UploadableField(mapping: 'thumbnail', fileNameProperty: 'thumbnail')]
    #[Assert\File(
        maxSize: '800k',
        maxSizeMessage: 'article.thumbnail.max_size',
        mimeTypes: ['image/png', 'image/jpeg'],
        mimeTypesMessage: 'article.thumbnail.mime_type'
    )]
    #[Assert\NotNull(message: 'article.thumbnail.not_null', groups: ['admin:form:new'])]
    private ?File $thumbnailFile = null;

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

    public function setThumbnailFile(?File $thumbnail = null)
    {
        $this->thumbnailFile = $thumbnail;

        if ($thumbnail) {
            $this->refreshUpdatedAt();
        }
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    public function setThumbnail(?string $thumbnail = null): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    #[SerializedName('type')]
    #[Groups(['thread:extend'])]
    public function getType(): ThreadType
    {
        return ThreadType::ARTICLE;
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
