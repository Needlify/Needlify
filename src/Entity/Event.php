<?php

namespace App\Entity;

use App\Entity\Interface\ThreadInterface;
use App\Repository\EventRepository;
use App\Service\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends Thread implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 350)]
    #[Assert\NotBlank(message: "Le message d'une évènement ne pas être vide")]
    #[Assert\Length(max: 350, maxMessage: "Le message d'une évènement ne peut pas dépasser {{ limite }} caractères")]
    private ?string $message = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): string
    {
        return ThreadType::EVENT->value;
    }

    public function getPreview(): string
    {
        return $this->getMessage();
    }
}
