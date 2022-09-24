<?php

namespace App\Entity\Interface;

interface PublicationInterface
{
    public function getType(): string;

    public function getPreview(): string;
}
