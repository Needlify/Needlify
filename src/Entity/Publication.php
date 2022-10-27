<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    Moodline::class => Moodline::class,
    Article::class => Article::class,
])]
#[ORM\HasLifecycleCallbacks]
abstract class Publication extends Thread
{
    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'publications')]
    #[Assert\NotNull(message: "Le topic d'une publication doit être renseigné")]
    #[Groups(['thread:extend'])]
    protected ?Topic $topic = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'publications')]
    #[Groups(['thread:extend'])]
    protected Collection $tags;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'publications')]
    protected ?User $author = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
