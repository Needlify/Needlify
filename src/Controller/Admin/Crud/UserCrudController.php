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

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use App\EventSubscriber\Admin\UserCrudPreSubmitSubscriber;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $encoder,
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormOptions(
                newFormOptions: ['validation_groups' => ['Default', 'auth:check:full']],
                editFormOptions: ['validation_groups' => function (FormInterface $form) {
                    // If the password is empty, we don't want to validate it.
                    $groups = ['Default'];

                    if (null !== $form->get('rawPassword')->getData()) {
                        $groups[] = 'auth:check:full';
                    }

                    return $groups;
                }]
            )
            ->setSearchFields(['email', 'username'])
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.user.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.user.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.user.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.user.details.title', [], 'admin'));
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        // We need to add the subscriber to the form builder so that we can update the submitted data before it is validated.
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        $formBuilder->addEventSubscriber(new UserCrudPreSubmitSubscriber($this->encoder));

        return $formBuilder;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $formBuilder->addEventSubscriber(new UserCrudPreSubmitSubscriber($this->encoder));

        return $formBuilder;
    }

    public function configureFilters(Filters $filters): Filters
    {
        /**
         * [
         *    "ROLE_USER" => "ROLE_USER",
         *    "ROLE_ADMIN" => "ROLE_ADMIN",
         * ].
         */
        $roleList = array_combine(
            array_keys($this->getParameter('security.role_hierarchy.roles')),
            array_keys($this->getParameter('security.role_hierarchy.roles'))
        );

        return $filters
            ->add(TextFilter::new('username'))
            ->add(TextFilter::new('email'))
            ->add(ArrayFilter::new('roles')->setChoices($roleList))
            ->add(DateTimeFilter::new('createdAt'))
            ->add(DateTimeFilter::new('updatedAt'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.user.column.id')
            ->onlyOnDetail();
        yield TextField::new('username', 'admin.crud.user.column.username');
        yield EmailField::new('email', 'admin.crud.user.column.email');
        yield ArrayField::new('roles', 'admin.crud.user.column.roles')
            ->setTemplatePath('admin/components/roles.html.twig')
            ->hideOnForm();

        yield FormField::addPanel('admin.crud.section.security')->onlyOnForms();
        yield TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setRequired(Crud::PAGE_NEW === $pageName)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'admin.crud.user.column.password_new'],
                'second_options' => ['label' => 'admin.crud.user.column.password_confirmation'],
            ])
            ->onlyOnForms();
        yield HiddenField::new('rawPassword')
            ->onlyOnForms();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('createdAt', 'admin.crud.user.column.created_at')
            // ->setTimezone('UTC')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.user.column.updated_at')
            // ->setTimezone('UTC')
            ->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.user.actions.create'));
        // ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
