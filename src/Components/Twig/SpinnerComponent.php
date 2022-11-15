<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('spinner', template: 'components/twig/spinner.html.twig')]
class SpinnerComponent
{
    public string $size = '22px';

    public string $color = 'currentColor';

    public string $borderWidth = '3px';
}
