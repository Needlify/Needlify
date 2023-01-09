<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\NewsletterAccount;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NewsletterAccountCrudController extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return NewsletterAccount::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('email')
            ->add('isVerified')
            ->add('isEnabled')
            ->add('verifiedAt')
            ->add('subscribedAt')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['email'])
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['subscribedAt' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.newsletter.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.newsletter.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.newsletter.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.newsletter.details.title', [], 'admin'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.newsletter.column.id')->onlyOnDetail();
        yield EmailField::new('email', 'admin.crud.newsletter.column.email');

        yield FormField::addPanel('admin.crud.section.verification');
        yield BooleanField::new('isVerified', 'admin.crud.newsletter.column.is_verified');
        yield BooleanField::new('isEnabled', 'admin.crud.newsletter.column.is_enabled')->hideWhenCreating();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('verifiedAt', 'admin.crud.newsletter.column.verified_at')->onlyOnDetail();
        yield DateTimeField::new('subscribedAt', 'admin.crud.newsletter.column.subscribed_at')->hideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.newsletter.actions.create'));
    }
}
