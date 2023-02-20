<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Enum\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use App\Entity\Interface\ThreadInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends Thread implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 240)] // 240 = 150 * 0.6
    #[Assert\Length(max: 240, maxMessage: 'event.content.length')]
    private ?string $content = null;

    #[Assert\Length(max: 150, maxMessage: 'event.raw_content.length')]
    #[Assert\NotBlank(message: 'event.raw_content.not_blank')]
    private ?string $rawContent = null;

    public function __construct(?string $content = null)
    {
        $this->setContent($content);
    }

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

    public function setRawContent(?string $content = null): self
    {
        if (null !== $content) {
            $this->rawContent = strip_tags($content);
        }

        return $this;
    }

    #[SerializedName('type')]
    #[Groups(['thread:basic'])]
    public function getType(): ThreadType
    {
        return ThreadType::EVENT;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:basic'])]
    public function getPreview(): string
    {
        return $this->getContent();
    }
}
