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
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BannerCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Banner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['endedAt' => 'DESC'])
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
            ->add(ChoiceFilter::new('type')
                ->setChoices(BannerType::array())
            )
            ->add(TextFilter::new('link'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.banner.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.banner.column.title')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 255]);
        yield ChoiceField::new('type', 'admin.crud.banner.column.type')
            ->setChoices(BannerType::cases())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => BannerType::class,
                'data' => BannerType::INFO,
            ])
            ->setColumns('col-md-6')
            ->onlyOnForms()
        ;
        yield ChoiceField::new('type', 'admin.crud.banner.column.type')
            ->setChoices(BannerType::array())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => BannerType::class,
            ])
            ->renderAsBadges()
            ->hideOnForm()
        ;
        yield IntegerField::new('priority', 'admin.crud.banner.column.priority')
            ->setColumns('col-md-6')
            ->setFormTypeOptions([
                'attr.max' => 32768,
                'attr.min' => -32768,
                'attr.step' => 1,
            ])
            ->hideOnIndex();
        yield TextEditorField::new('content', 'admin.crud.banner.column.content')
            ->setTrixEditorConfig(TrixEditorConfiguratorService::DEFAULT_TRIX_CONFIGURATION)
            ->setColumns(12)
            ->addWebpackEncoreEntries('admin_editor_trix_default', 'admin_editor_trix_onlyText')
            ->formatValue(fn (string $value) => $value) // To render content as html rather than just text
        ;
        yield UrlField::new('link', 'admin.crud.banner.column.link')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 1000])
            ->formatValue(fn (string $link) => $link); // Pour afficher le badge quand c'est null

        yield FormField::addPanel('admin.crud.section.dates');
        yield DateTimeField::new('startedAt', 'admin.crud.banner.column.started_at')
            ->setColumns('col-md-6')
            ->setFormTypeOptions([
                'with_seconds' => false,
                'attr.class' => 'w-100',
            ]);
        yield DateTimeField::new('endedAt', 'admin.crud.banner.column.ended_at')
            ->setColumns('col-md-6')
            ->setFormTypeOptions([
                'with_seconds' => false,
                'attr.class' => 'w-100',
            ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.banner.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
