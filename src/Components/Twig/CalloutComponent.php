<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Twig;

use App\Enum\CalloutType;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('callout', template: 'components/twig/callout.html.twig')]
class CalloutComponent
{
    public ?CalloutType $type = null;

    public string $content;

    #[ExposeInTemplate('icon')]
    public function getIcon()
    {
        switch ($this->type) {
            case CalloutType::INFO:
                return 'info';
            case CalloutType::SUCCESS:
                return 'check-circle';
            case CalloutType::WARNING:
                return 'alert-circle';
            case CalloutType::ALERT:
                return 'x-circle';
            default:
                return null;
        }
    }

    #[ExposeInTemplate('typeClass')]
    public function getTypeClass()
    {
        switch ($this->type) {
            case CalloutType::INFO:
                return 'info';
            case CalloutType::SUCCESS:
                return 'success';
            case CalloutType::WARNING:
                return 'warning';
            case CalloutType::ALERT:
                return 'alert';
            default:
                return null;
        }
    }
}
