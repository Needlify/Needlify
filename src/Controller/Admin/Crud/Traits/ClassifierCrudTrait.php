<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud\Traits;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

trait ClassifierCrudTrait
{
    public function defaultClassifierFieldConfiguration(string $pageName, string $classifierFqcn): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('name');
        yield TextField::new('slug')->onlyOnDetail();

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('lastUseAt')->hideOnForm();

        yield FormField::addPanel('Associations')->hideOnForm();
        yield AssociationField::new('publications')
            ->setTemplatePath('admin/components/publications.html.twig')
            ->addWebpackEncoreEntries('admin:component:publications')
            ->hideOnForm();
    }

    public function defaultClassifierCrudConfiguration(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('d LLL. yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}
