<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    Moodline::class => Moodline::class,
    Article::class => Article::class,
])]
#[ORM\HasLifecycleCallbacks]
abstract class Publication
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[Assert\NotNull(message: "L'auteur d'une publication doit être renseigné")]
    protected ?User $author = null;

    #[ORM\Column]
    protected ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[Assert\NotNull(message: "Le topic d'une publication doit être renseigné")]
    protected ?Topic $topic = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'publications')]
    protected Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    #[ORM\PrePersist]
    public function setPublishedAt(): void
    {
        $this->publishedAt = new DateTimeImmutable();
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = new ArrayCollection($tags);

        return $this;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
