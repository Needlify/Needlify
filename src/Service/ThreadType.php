<?php

namespace App\Service;

enum ThreadType: string
{
    case ARTICLE = 'article';
    case MOODLINE = 'moodline';
    case EVENT = 'event';
}
