<?php

namespace App\Entity;

use App\Repository\TagRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: "Le nom d'un tag ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage: "Le nom d'un tag ne peut pas dépasser {{ limite }} caractères")]
    private ?string $name = null;

    // Is set once when the tag in created (see: setCreatedAt())
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Is set once when the tag in created (see: updateLastUsedAt())
    // Is also updated when a post contains it
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUsedAt = null;

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
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getLastUsedAt(): ?\DateTimeInterface
    {
        return $this->lastUsedAt;
    }

    #[ORM\PrePersist]
    public function updateLastUsedAt(): void
    {
        $this->lastUsedAt = new DateTime();
    }
}
