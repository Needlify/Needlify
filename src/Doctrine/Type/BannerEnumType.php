<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine\Type;

use App\Enum\BannerType;

class BannerEnumType extends AbstractEnumType
{
    public const NAME = 'banner_enum_type';

    public function getEnum(): string
    {
        return BannerType::class;
    }

    public function getName()
    {
        return self::NAME;
    }
}
