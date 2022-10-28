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
use App\Repository\EventRepository;
use App\Entity\Interface\ThreadInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends Thread implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 240)] // 240 = 150 * 0.6
    #[Assert\Length(max: 240, maxMessage: "Le message d'une évènement ne peut pas dépasser {{ limit }} caractères")]
    private ?string $message = null;

    #[Assert\Length(max: 150, maxMessage: "Le message brut d'une évènement ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank(message: "Le message brut d'une évènement ne pas être vide")]
    private ?string $rawMessage = null;

    public function __construct(?string $message = null)
    {
        $this->setMessage($message);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message = null): self
    {
        $this->message = $message;

        if (null !== $message) {
            $this->rawMessage = strip_tags($message);
        }

        return $this;
    }

    public function getRawMessage(): ?string
    {
        return $this->rawMessage;
    }

    #[SerializedName('type')]
    #[Groups(['thread:extend'])]
    public function getType(): string
    {
        return ThreadType::EVENT->value;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:extend'])]
    public function getPreview(): string
    {
        return $this->getMessage();
    }
}
