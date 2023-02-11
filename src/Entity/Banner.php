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

use App\Enum\BannerType;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BannerRepository;
use App\Doctrine\Type\BannerEnumType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BannerRepository::class)]
class Banner
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    // TODO Modifier les messages d'erreur

    #[ORM\Column(length: 1600, type: Types::STRING)] // 1600 = 1000 * 0.6
    #[Assert\NotBlank(message: 'article.title.not_blank')]
    #[Assert\Length(max: 1600, maxMessage: 'article.title.length')]
    private ?string $content = null;

    #[Assert\Length(max: 1000, maxMessage: 'event.raw_content.length')]
    #[Assert\NotBlank(message: 'event.raw_content.not_blank')]
    private ?string $rawContent = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: -32768,
        minMessage: 'event.raw_content.length',
        max: 32768,
        maxMessage: 'event.raw_content.length'
    )]
    private ?int $priority = 0;

    #[ORM\Column(type: BannerEnumType::NAME, length: 20)]
    #[Assert\Type(BannerType::class)]
    private ?BannerType $type = null;

    #[ORM\Column(length: 255, type: Types::STRING, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'event.raw_content.length')]
    private ?string $title = null;

    #[ORM\Column(length: 1000, type: Types::STRING, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: 'event.raw_content.length')]
    private ?string $link = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getRawContent(): ?string
    {
        return $this->rawContent;
    }

    public function setRawContent(?string $content = null): self
    {
        if (null !== $content) {
            $this->rawContent = strip_tags($content);
        }

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getType(): ?BannerType
    {
        return $this->type;
    }

    public function setType(BannerType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
