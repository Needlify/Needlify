<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use App\Trait\EnumUtilityTrait;

enum ExceptionCode: int
{
    use EnumUtilityTrait;

    /* 400 Bad Requests */
    case INVALID_CSRF_TOKEN = 400_0;

    case INVALID_IMAGE_SIGNATURE = 400_1;

    case INVALID_QUERY_PARAM = 400_2;
    case MISSING_QUERY_PARAM_PARAMETER = 400_3;
    case INVALID_QUERY_PARAM_REQUIREMENT = 400_4;

    case NEWSLETTER_REGISTRATION_TOKEN_MISSING = 400_5;
    case NEWSLETTER_REGISTRATION_INVALID_TOKEN = 400_6;

    case MISSING_FILE_PARAM = 400_7;
    case INVALID_MIME_TYPE = 400_8;
    case UPLOADED_FILE_ERROR = 400_9;
    case UNREADABLE_UPLOADED_FILE = 400_10;
    case UPLOADED_FILE_TOO_BIG = 400_11;

    /* 403 Access Forbidden */
    case INVALID_NEWSLETTER_CREDENTIALS = 403_0;
    case JSON_LOGIN_REDIRECT_UNAVAILABLE = 403_1;

    /* 404 Not Found */
    case RESSOURCE_NOT_FOUND = 404_0;
}
