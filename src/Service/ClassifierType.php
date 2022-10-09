<?php

namespace App\Service;

enum ClassifierType: string
{
    use EnumTrait;

    case TOPIC = 'topic';
    case TAG = 'tag';
}
