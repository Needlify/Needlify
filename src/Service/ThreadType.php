<?php

namespace App\Service;

enum ThreadType: string
{
    use EnumTrait;

    case ARTICLE = 'article';
    case MOODLINE = 'moodline';
    case EVENT = 'event';
}
