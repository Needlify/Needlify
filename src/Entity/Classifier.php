<?php

namespace App\Entity;

use App\Repository\ClassifierRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;

use function Symfony\Component\String\u;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassifierRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    Tag::class => Tag::class,
    Topic::class => Topic::class,
])]
#[ORM\HasLifecycleCallbacks]
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
