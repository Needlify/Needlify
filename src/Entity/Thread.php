<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
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

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected ?\DateTimeImmutable $publishedAt = null;

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

    #[ORM\PrePersist]
    public function setPublishedAt(): void
    {
        $this->publishedAt = new DateTimeImmutable();
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
