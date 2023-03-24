<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Enum;

use App\Trait\EnumUtilityTrait;

enum CourseDifficultyType: string
{
    use EnumUtilityTrait;

    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';
    case EXPERT = 'expert';
}
