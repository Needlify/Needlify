<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

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
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['thread:extend'])]
    protected ?\DateTimeImmutable $publishedAt = null; // publishedAt is the dateTime in UTC/GMT

    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[Assert\NotNull(message: "L'auteur d'un thread doit être renseigné")]
    protected ?User $author = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getPublishedAtToISO8601(): string
    {
        return $this->publishedAt->format(DateTime::ATOM);
    }

    #[ORM\PrePersist]
    public function setPublishedAt(): void
    {
        $this->publishedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
