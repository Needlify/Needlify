<?php

namespace App\Entity\Interface;

interface ThreadInterface
{
    public function getType(): string;

    public function getPreview(): string;
}
