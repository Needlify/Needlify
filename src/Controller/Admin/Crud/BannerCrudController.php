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

use App\Entity\Banner;
use App\Enum\BannerType;
use App\Service\TrixEditorConfiguratorService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BannerCrudController extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Banner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['title', 'content', 'type', 'link'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.banner.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.banner.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.banner.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.banner.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title'))
            ->add(TextFilter::new('content'))
            ->add(DateTimeFilter::new('startedAt'))
            ->add(DatetimeFilter::new('endedAt'))
            ->add(NumericFilter::new('priority'))
            ->add(ChoiceFilter::new('type')->setChoices(BannerType::cases()))
            ->add(TextFilter::new('link'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.event.column.id')->onlyOnDetail();
        yield TextEditorField::new('content', 'admin.crud.event.column.content')
            ->setTrixEditorConfig(TrixEditorConfiguratorService::DEFAULT_TRIX_CONFIGURATION)
            ->addWebpackEncoreEntries('admin:trix:default', 'admin:trix:onlyText')
            ->formatValue(fn (string $value) => $value) // To render content as html rather than just text
        ;

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('startedAt', 'admin.crud.event.column.published_at')->hideOnForm();
        yield DateTimeField::new('endedAt', 'admin.crud.event.column.updated_at')->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.event.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
