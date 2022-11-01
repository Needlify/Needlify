<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud\Traits;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

trait ThreadCrudTrait
{
    protected static array $defaultEditorConfig = [
        'textAttributes' => [
            'frozen' => [
                'style' => [
                    'backgroundColor' => 'unset',
                ],
            ],
        ],
    ];

    public function defaultThreadCrudConfiguration(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('d LLL. yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['publishedAt' => 'DESC']);
    }
}
