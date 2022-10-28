<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Crud\Traits\ThreadCrudTrait;
use App\Controller\Admin\Crud\Traits\ContentCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;
    use ContentCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('title');
        yield AssociationField::new('author')->onlyOnDetail();
        yield TextField::new('slug')->onlyOnDetail();
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield TextEditorField::new('description')->onlyOnIndex();

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('publishedAt')->hideOnForm();

        yield FormField::addPanel('Associations');
        yield AssociationField::new('topic')->setRequired(true);
        yield AssociationField::new('tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin:component:tags', 'component:vue');

        yield FormField::addPanel('Content');
        yield TextEditorField::new('content')
            ->formatValue(fn (string $value) => $value); // To render content as html rather than just text
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->defaultContentActionConfiguration($actions, Article::class);
    }
}
