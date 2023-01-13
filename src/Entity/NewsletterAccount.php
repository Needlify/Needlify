<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsletterAccountRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: NewsletterAccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'newsletter_account.email.unique')]
class NewsletterAccount
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\NotBlank(message: 'newsletter_account.email.not_blank')]
    #[Assert\Email(message: 'newsletter_account.email.email')]
    #[Assert\Length(max: 180, maxMessage: 'newsletter_account.email.length')]
    private ?string $email = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $subscribedAt = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isEnabled = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastRetryAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(): self
    {
        $this->verifiedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        return $this;
    }

    public function resetVerifiedAt(): self
    {
        $this->verifiedAt = null;

        return $this;
    }

    public function getSubscribedAt(): ?\DateTimeImmutable
    {
        return $this->subscribedAt;
    }

    #[ORM\PrePersist]
    public function setSubscribedAt()
    {
        $this->subscribedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getLastRetryAt(): ?\DateTimeInterface
    {
        return $this->lastRetryAt;
    }

    public function updateLastRetryAt(): self
    {
        $this->lastRetryAt = new \DateTime('now', new \DateTimeZone('UTC'));

        return $this;
    }

    public function canRetryConfirmation()
    {
        if (null === $this->getLastRetryAt()) {
            return true;
        }

        $now = (new \DateTime('now', new \DateTimeZone('UTC')))->getTimestamp();
        $lastRetryAt = $this->getLastRetryAt()->getTimestamp();

        if ($now - $lastRetryAt >= 60 * 3) {
            return true;
        }

        return false;
    }
}
