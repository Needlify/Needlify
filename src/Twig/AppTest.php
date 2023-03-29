<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Twig\TwigTest;
use Twig\Extension\AbstractExtension;

class AppTest extends AbstractExtension
{
    /**
     * @codeCoverageIgnore
     */
    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'instanceOf']),
        ];
    }

    public function instanceOf($el, string $fqcn)
    {
        return $el instanceof $fqcn;
    }
}
