<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Moodline;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MoodlineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Moodline::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
        ];
    }
}
