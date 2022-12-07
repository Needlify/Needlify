<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Twig;

use App\Entity\Enum\ClassifierType;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('breadcrumb', template: 'components/twig/breadcrumb.html.twig')]
class BreadcrumbComponent
{
    public string $name;

    public ClassifierType $type;

    #[ExposeInTemplate('icon')]
    public function getIcon()
    {
        switch ($this->type) {
            case ClassifierType::TAG:
                return 'hash';
            case ClassifierType::TOPIC:
                return 'tag';

            default:
                return '';
        }
    }
}
