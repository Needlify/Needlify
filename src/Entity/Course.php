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
use App\Enum\CourseDifficultyType;
use App\Repository\CourseRepository;
use App\Entity\Interface\ThreadInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Course extends Document implements ThreadInterface
{
    #[ORM\Column(type: Types::STRING, length: 20, enumType: CourseDifficultyType::class)]
    #[Assert\Type(CourseDifficultyType::class, message: 'course.difficulty.type')]
    private ?CourseDifficultyType $difficulty = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Lesson::class, cascade: ['remove'])]
    private Collection $lessons;

    public function __construct()
    {
        parent::__construct();
        $this->lessons = new ArrayCollection();
    }

    #[SerializedName('type')]
    #[Groups(['thread:basic'])]
    public function getType(): ThreadType
    {
        return ThreadType::COURSE;
    }

    #[SerializedName('preview')]
    #[Groups(['thread:basic'])]
    public function getPreview(): string
    {
        return $this->getDescription();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getDifficulty(): ?CourseDifficultyType
    {
        return $this->difficulty;
    }

    public function setDifficulty(?CourseDifficultyType $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): self
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCourse($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): self
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getCourse() === $this) {
                $lesson->setCourse(null);
            }
        }

        return $this;
    }

    // public function getFirstLesson()
    // {
    //     return $this->lessons->filter(fn (Lesson $lesson) => null === $lesson->getPrevious())[0];
    // }

    // public function getLastLesson()
    // {
    //     return $this->lessons->filter(fn (Lesson $lesson) => null === $lesson->getNext())[0];
    // }
}
