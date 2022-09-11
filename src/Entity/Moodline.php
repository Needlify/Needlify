<?php

namespace App\Entity;

use App\Repository\MoodlineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MoodlineRepository::class)]
class Moodline extends Publication
{
    #[ORM\Column(type: Types::TEXT, length: 350)]
    #[Assert\NotBlank(message: "Le contenu d'une moodline ne pas être vide")]
    #[Assert\Length(max: 350, maxMessage: "Le contenu d'une moodline ne peut pas dépasser {{ limite }} caractères")]
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
