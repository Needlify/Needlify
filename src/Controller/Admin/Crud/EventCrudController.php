<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Event;
use App\Trait\Admin\Crud\ThreadCrudTrait;
use App\Service\TrixEditorConfiguratorService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use function Symfony\Component\Translation\t;

class EventCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
            ->setSearchFields(['content'])
            ->setPageTitle(Crud::PAGE_INDEX, t('admin.crud.event.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('admin.crud.event.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, t('admin.crud.event.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, t('admin.crud.event.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('content'))
            ->add(BooleanFilter::new('private'))
            ->add(DateTimeFilter::new('publishedAt'))
            ->add(DateTimeFilter::new('updatedAt'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.event.column.id')->onlyOnDetail();
        yield BooleanField::new('private', 'admin.crud.event.column.private')->setHelp('admin.crud.event.column.private.help');
        yield TextEditorField::new('content', 'admin.crud.event.column.content')
            ->setTrixEditorConfig(TrixEditorConfiguratorService::DEFAULT_TRIX_CONFIGURATION)
            ->setColumns(12)
            ->addWebpackEncoreEntries('admin:trix:default', 'admin:trix:onlyText')
            ->formatValue(fn (string $value) => $value) // To render content as html rather than just text
        ;

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.event.column.published_at')->setTimezone('UTC')->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.event.column.updated_at')->setTimezone('UTC')->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.event.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
