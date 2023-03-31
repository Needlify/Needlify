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

use App\Enum\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use App\Entity\Interface\ThreadInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Article extends Document implements ThreadInterface
{
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
    #[Groups(['thread:basic'])]
    public function getType(): ThreadType
    {
        return ThreadType::ARTICLE;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:basic'])]
    public function getPreview(): string
    {
        return $this->getDescription();
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
