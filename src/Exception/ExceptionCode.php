<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use App\Trait\EnumUtilityTrait;

enum ExceptionCode: int
{
    use EnumUtilityTrait;

    case RESSOURCE_NOT_FOUND = 4000;
    case INVALID_QUERY_PARAM = 4001;
    case INVALID_CSRF_TOKEN = 4002;

    case INVALID_IMAGE_SIGNATURE = 4003;
}
