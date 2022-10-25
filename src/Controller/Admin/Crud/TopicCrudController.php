<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Topic;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use App\Controller\Admin\Crud\Traits\ClassifierCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TopicCrudController extends AbstractCrudController
{
    use ClassifierCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setDateTimeFormat('d LLL. yyyy HH:mm:ss ZZZZ');
    }

    public function configureFields(string $pageName): iterable
    {
        return $this->defaultFieldConfiguration($pageName, Topic::class);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->defaultActionConfiguration($actions, Topic::class);
    }
}
