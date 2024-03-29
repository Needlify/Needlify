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

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'user.email.unique')]
#[UniqueEntity(fields: ['username'], message: 'user.username.unique')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    #[Assert\NotBlank(message: 'user.username.not_blank')]
    #[Assert\Length(max: 50, maxMessage: 'user.username.length')]
    #[Assert\Regex(pattern: '/^[\w\-\.]*$/', message: 'user.username.regex')]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\NotBlank(message: 'user.email.not_blank')]
    #[Assert\Email(message: 'user.email.email')]
    #[Assert\Length(max: 180, maxMessage: 'user.email.length')]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    #[Ignore]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(message: 'user.password.not_blank', groups: ['auth:check:full'])]
    #[Ignore]
    private ?string $password = null;

    #[Assert\NotBlank(message: 'user.password.not_blank', groups: ['auth:check:full'])]
    #[Assert\NotCompromisedPassword(message: 'user.raw_password.not_compromised_password', groups: ['auth:check:full'])]
    #[Assert\Length(
        min: 8,
        minMessage: 'user.raw_password.min_length',
        max: 50,
        maxMessage: 'user.raw_password.max_length',
        groups: ['auth:check:full']
    )]
    #[Assert\NotEqualTo(propertyPath: 'username', message: 'user.raw_password.not_equal_to')]
    #[Assert\Regex(pattern: '/^.*?[A-Z].*?$/', message: 'user.raw_password.upper_case', groups: ['auth:check:full'])]
    #[Assert\Regex(pattern: '/^.*?[0-9].*?$/', message: 'user.raw_password.number', groups: ['auth:check:full'])]
    #[Assert\Regex(pattern: '/^.*?[!"`\'#%&,:;<>=@{}~\$\(\)\*\+\/\\\?\[\]\^\|].*?$/', message: 'user.raw_password.special_char', groups: ['auth:check:full'])]
    private ?string $rawPassword = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Publication::class, cascade: ['remove'])]
    private Collection $publications;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    #[Ignore]
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        $this->roles[] = $role;
        $this->roles = array_unique($this->roles);

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        if (null !== $password) {
            $this->password = $password;
        }

        return $this;
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(?string $rawPassword): self
    {
        if (null !== $rawPassword) {
            $this->rawPassword = $rawPassword;
        }

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        // $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function autoUpdatedAt()
    {
        $this->refreshUpdatedAt();
    }

    public function refreshUpdatedAt()
    {
        // $this->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @return Collection<int, Thread>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setAuthor($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getAuthor() === $this) {
                $publication->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }
}
