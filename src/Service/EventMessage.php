<?php

namespace App\Service;

enum EventMessage: string
{
    case NEW_TOPIC = 'Le topic **%s** a été créé';

    public function format(array $params): string
    {
        $parsedown = ParsedownFactory::create();

        return $parsedown->line(vsprintf($this->value, $params));
    }
}
