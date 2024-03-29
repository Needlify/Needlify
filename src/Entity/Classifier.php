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
use App\Repository\ClassifierRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: ClassifierRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    Tag::class => Tag::class,
    Topic::class => Topic::class,
])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(['slug'], 'classifier.slug.unique')]
abstract class Classifier
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Assert\NotBlank(message: 'classifier.name.not_blank')]
    #[Assert\Length(max: 50, maxMessage: 'classifier.name.length')]
    #[Groups(['thread:basic'])]
    protected ?string $name = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    protected ?\DateTimeImmutable $lastUseAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    protected ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: 'classifier.slug.length')]
    #[Groups(['thread:basic'])]
    protected ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'classifier.description.length')]
    private ?string $description = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        // $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getLastUseAt(): ?\DateTimeImmutable
    {
        return $this->lastUseAt;
    }

    #[ORM\PrePersist]
    public function refreshLastUseAt(): void
    {
        // $this->lastUseAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->lastUseAt = new \DateTimeImmutable('now');
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    #[ORM\PrePersist]
    public function setSlug(): void
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug(
            u($this->name)->lower() . ' ' . uniqid()
        );
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
