<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

enum EventMessage: string
{
    case NEW_TOPIC = 'Le topic **[%s](%s)** a été créé';

    public function format(array $params): string
    {
        return $this->toMarkdown(vsprintf($this->value, $params));
    }

    private function toMarkdown(string $formatedMarkdown)
    {
        $parsedown = ParsedownFactory::create();

        return $parsedown->text($formatedMarkdown);
    }
}
