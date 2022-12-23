<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    Publication::class => Publication::class,
    Article::class => Article::class,
    Moodline::class => Moodline::class,
    Event::class => Event::class,
])]
abstract class Thread
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['thread:extend'])]
    protected ?\DateTimeImmutable $publishedAt = null; // publishedAt is in UTC/GMT

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $updatedAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getPublishedAtToISO8601(): string
    {
        return $this->publishedAt->format(\DateTime::ATOM);
    }

    #[ORM\PrePersist]
    public function setPublishedAt(): void
    {
        $this->publishedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
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
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
