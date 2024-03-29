<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Interface;

use App\Enum\ThreadType;
use App\Enum\ClassifierType;

interface EntityTypeInterface
{
    public function getType(): ThreadType|ClassifierType;
}
