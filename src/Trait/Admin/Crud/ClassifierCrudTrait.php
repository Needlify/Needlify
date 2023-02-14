<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Trait\Admin\Crud;

use App\Entity\Topic;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

trait ClassifierCrudTrait
{
    public function defaultFilterConfiguration(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name'))
            ->add(DateTimeFilter::new('createdAt'))
            ->add(DateTimeFilter::new('lastUseAt'))
            ->add(DateTimeFilter::new('updatedAt'))
        ;
    }

    public function defaultClassifierFieldConfiguration(string $pageName, string $classifierFqcn): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.classifier.column.id')->onlyOnDetail();
        yield TextField::new('name', 'admin.crud.classifier.column.name');
        yield TextField::new('slug', 'admin.crud.classifier.column.slug')->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('createdAt', 'admin.crud.classifier.column.created_at')
            // ->setTimezone('UTC')
            ->hideOnForm();
        yield DateTimeField::new('lastUseAt', 'admin.crud.classifier.column.last_use_at')
            // ->setTimezone('UTC')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.classifier.column.updated_at')
            // ->setTimezone('UTC')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.associations')->hideOnForm();

        if (Topic::class === $classifierFqcn) {
            yield AssociationField::new('event', 'admin.crud.classifier.column.event')->hideOnForm();
        }

        yield AssociationField::new('publications', 'admin.crud.classifier.column.publications')->hideOnForm();
    }

    public function defaultClassifierCrudConfiguration(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['name']);
    }
}
