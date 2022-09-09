<?php

namespace App\Entity;

use App\Repository\MoodlineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoodlineRepository::class)]
class Moodline extends Publication
{
    #[ORM\Column(length: 255)]
    private ?string $content = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
