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

    #[SerializedName('type')]
    #[Groups(['thread:extend'])]
    public function getType(): string
    {
        return ThreadType::MOODLINE->value;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:extend'])]
    public function getPreview(): string
    {
        return $this->getContent();
    }
}
