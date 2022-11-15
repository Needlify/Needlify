<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Service\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MoodlineRepository;
use App\Entity\Interface\ThreadInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MoodlineRepository::class)]
class Moodline extends Publication implements ThreadInterface
{
    #[ORM\Column(type: Types::TEXT, length: 800)]
    #[Assert\Length(max: 800, maxMessage: "Le contenu d'une moodline ne peut pas dépasser {{ limit }} caractères")]
    private ?string $content = null;

    #[Assert\Length(max: 500, maxMessage: "Le contenu brut d'une moodline ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank(message: "Le contenu brut d'une moodline ne pas être vide")]
    private ?string $rawContent = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content = null): self
    {
        $this->content = $content;
        $this->setRawContent($content);

        return $this;
    }

    public function getRawContent(): ?string
    {
        return $this->rawContent;
    }

    public function setRawContent(?string $content = null)
    {
        if (null !== $content) {
            $this->rawContent = strip_tags($content);
        }

        return $this;
    }

    #[SerializedName('type')]
    #[Groups(['thread:extend'])]
    public function getType(): ThreadType
    {
        return ThreadType::MOODLINE;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:extend'])]
    public function getPreview(): string
    {
        return $this->getContent();
    }

    public function __toString()
    {
        return $this->id->toRfc4122();
    }
}
