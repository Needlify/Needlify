<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Service\ClassifierType;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\Collection;
use App\Entity\Interface\EntityTypeInterface;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic extends Classifier implements EntityTypeInterface
{
    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: Publication::class, cascade: ['remove'])]
    private Collection $publications;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Event $event = null;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setTopic($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getTopic() === $this) {
                $publication->setTopic(null);
            }
        }

        return $this;
    }

    public function getType(): ClassifierType
    {
        return ClassifierType::TOPIC;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
