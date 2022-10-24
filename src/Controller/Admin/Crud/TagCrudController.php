<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Controller\Admin\Crud\Traits\ClassifierCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TagCrudController extends AbstractCrudController
{
    use ClassifierCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setDateTimeFormat('d LLL. yyyy HH:mm:ss ZZZZ');
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('name');
        yield TextField::new('slug')->onlyOnDetail();

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('lastUseAt')->hideOnForm();

        yield FormField::addPanel('Associations')->hideOnForm();
        yield AssociationField::new('publications')->hideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->defaultActionConfiguration($actions, Tag::class);
    }
}
