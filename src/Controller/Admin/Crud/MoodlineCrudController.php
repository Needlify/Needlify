<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Moodline;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use App\Controller\Admin\Crud\Traits\ThreadCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MoodlineCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Moodline::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
            ->setSearchFields(['content', 'topic.name', 'tags.name']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();

        yield TextEditorField::new('content')->onlyOnIndex();
        yield TextareaField::new('content')
            ->hideOnIndex()
            ->setMaxLength(350)
            ->setFormTypeOption('attr.maxLength', 350);

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('publishedAt')->hideOnForm();

        yield FormField::addPanel('Associations');
        yield AssociationField::new('topic');
        yield AssociationField::new('tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin:component:tags', 'component:vue');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
}
