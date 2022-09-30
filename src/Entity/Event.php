<?php

namespace App\Entity;

use App\Entity\Interface\ThreadInterface;
use App\Repository\EventRepository;
use App\Service\ThreadType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends Thread implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 350)]
    #[Assert\NotBlank(message: "Le message d'une évènement ne pas être vide")]
    #[Assert\Length(max: 350, maxMessage: "Le message d'une évènement ne peut pas dépasser {{ limite }} caractères")]
    private ?string $message = null;

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

        return $this;
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
