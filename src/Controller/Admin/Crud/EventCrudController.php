<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Event;
use App\Trait\TranslationTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use App\Controller\Admin\Crud\Traits\ThreadCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;
    use TranslationTrait;

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters->add(DateTimeFilter::new('publishedAt'));
    // }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
            ->setSearchFields(['content'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.event.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.event.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.event.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.event.details.title', [], 'admin'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextEditorField::new('content', 'admin.crud.event.column.content')
            ->setTrixEditorConfig(self::$defaultEditorConfig)
            ->setNumOfRows(1)
            ->addWebpackEncoreEntries('admin:trix:default', 'admin:trix:onlyText')
            ->formatValue(fn (string $value) => $value) // To render content as html rather than just text
        ;

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.event.column.published_at')->hideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.event.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
