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

use App\Entity\Moodline;
use App\Trait\Admin\Crud\ThreadCrudTrait;
use App\Service\TrixEditorConfiguratorService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MoodlineCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;

    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Moodline::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
            ->setSearchFields(['content', 'topic.name', 'tags.name', 'author.username', 'author.email'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.moodline.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.moodline.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.moodline.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.moodline.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('content'))
            ->add(BooleanFilter::new('private'))
            ->add(EntityFilter::new('author'))
            ->add(DateTimeFilter::new('publishedAt'))
            ->add(DateTimeFilter::new('updatedAt'))
            ->add(EntityFilter::new('topic'))
            ->add(EntityFilter::new('tags'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.moodline.column.id')->onlyOnDetail();
        yield BooleanField::new('private', 'admin.crud.moodline.column.private')->setHelp('admin.crud.moodline.column.private.help');
        yield TextEditorField::new('content', 'admin.crud.moodline.column.content')
            ->setTrixEditorConfig(TrixEditorConfiguratorService::DEFAULT_TRIX_CONFIGURATION)
            ->setColumns(12)
            ->addWebpackEncoreEntries('admin_trix_default', 'admin_trix_onlyText')
            ->formatValue(fn (string $value) => $value);

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.moodline.column.published_at')
            // ->setTimezone('UTC')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.moodline.column.updated_at')
            // ->setTimezone('UTC')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.associations');
        yield AssociationField::new('topic', 'admin.crud.moodline.column.topic')
            ->setRequired(true)
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6');
        yield AssociationField::new('tags', 'admin.crud.moodline.column.tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.moodline.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
