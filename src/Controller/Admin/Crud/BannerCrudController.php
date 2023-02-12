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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use function Symfony\Component\Translation\t;

class BannerCrudController extends AbstractCrudController
{
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
            ->setPageTitle(Crud::PAGE_INDEX, t('admin.crud.banner.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('admin.crud.banner.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, t('admin.crud.banner.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, t('admin.crud.banner.details.title', [], 'admin'));
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
                ->setChoices(BannerType::arrayInverted())
            )
            ->add(TextFilter::new('link'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.banner.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.banner.column.title');
        yield IntegerField::new('priority', 'admin.crud.banner.column.priority')->hideOnIndex();
        yield ChoiceField::new('type', 'admin.crud.banner.column.type')
            ->setChoices(BannerType::cases())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => BannerType::class,
            ])
            ->onlyOnForms()
        ;
        yield ChoiceField::new('type', 'admin.crud.banner.column.type')
            ->setChoices(BannerType::arrayInverted())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => BannerType::class,
            ])
            ->renderAsBadges()
            ->hideOnForm()
        ;
        yield TextEditorField::new('content', 'admin.crud.banner.column.content')
            ->setTrixEditorConfig(TrixEditorConfiguratorService::DEFAULT_TRIX_CONFIGURATION)
            ->setColumns(12)
            ->addWebpackEncoreEntries('admin:trix:default', 'admin:trix:onlyText')
            ->formatValue(fn (string $value) => $value) // To render content as html rather than just text
        ;
        yield UrlField::new('link', 'admin.crud.banner.column.link')
            ->formatValue(fn (string $link) => $link); // Pour afficher le badge quand c'est null

        yield FormField::addPanel('admin.crud.section.dates');
        yield DateTimeField::new('startedAt', 'admin.crud.banner.column.started_at')
            ->setFormTypeOptions([
                'with_seconds' => false,
            ])
            ->setTimezone('UTC');
        yield DateTimeField::new('endedAt', 'admin.crud.banner.column.ended_at')
            ->setFormTypeOptions([
                'with_seconds' => false,
            ])
            ->setTimezone('UTC');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.banner.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
        ;
    }
}
