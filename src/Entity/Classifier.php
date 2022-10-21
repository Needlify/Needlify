<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
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
#[UniqueEntity(['name'], 'Ce nom est déjà utilisé')]
abstract class Classifier
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    #[Assert\NotBlank(message: "Le nom d'un classificateur ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage: "Le nom d'un classificateur ne peut pas dépasser {{ limite }} caractères")]
    #[Groups(['thread:extend'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUseAt = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Assert\NotBlank(message: "Le slug d'un classificateur ne pas être vide")]
    #[Assert\Length(max: 50, maxMessage: "Le slug d'un classificateur ne peut pas dépasser {{ limite }} caractères")]
    #[Groups(['thread:extend'])]
    private ?string $slug = null;

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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getLastUseAt(): ?DateTimeInterface
    {
        return $this->lastUseAt;
    }

    #[ORM\PrePersist]
    public function updateLastUseAt(): void
    {
        $this->lastUseAt = new DateTime();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    #[ORM\PrePersist]
    public function setSlug(): void
    {
        $slugger = new AsciiSlugger();
        $this->slug = u($slugger->slug($this->name))->lower();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
