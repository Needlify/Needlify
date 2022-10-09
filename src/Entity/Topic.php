<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic extends Classifier
{
    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: Publication::class)]
    private Collection $publications;

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
}
