<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Enum;

use App\Trait\EnumUtilityTrait;
use App\Service\ParsedownFactory;

enum EventMessage: string
{
    use EnumUtilityTrait;

    case NEW_TOPIC = 'The topic **[%s](%s)** has been created';

    public function format(array $params): string
    {
        return $this->toMarkdown(vsprintf($this->value, $params));
    }

    private function toMarkdown(string $formatedMarkdown)
    {
        $parsedown = ParsedownFactory::create();

        return '<div>' . $parsedown->line($formatedMarkdown) . '</div>';
    }
}
