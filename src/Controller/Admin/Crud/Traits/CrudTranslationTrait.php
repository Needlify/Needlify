<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud\Traits;

use Symfony\Contracts\Translation\TranslatorInterface;

trait CrudTranslationTrait
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }
}
