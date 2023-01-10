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
    case INVALID_CSRF_TOKEN = 4001;

    case INVALID_IMAGE_SIGNATURE = 4002;

    case INVALID_QUERY_PARAM = 4003;
    case MISSING_QUERY_PARAM_PARAMETER = 4004;
    case INVALID_QUERY_PARAM_REQUIREMENT = 4005;

    case NEWSLETTER_REGISTRATION_TOKEN_MISSING = 4006;
    case NEWSLETTER_REGISTRATION_INVALID_TOKEN = 4007;
}
