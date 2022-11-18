<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\Article;
use App\Service\ParsedownFactory;
use App\Admin\Field\MarkdownField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Crud\Traits\ThreadCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Controller\Admin\Crud\Traits\ContentCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
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
        $crudValue = $this->defaultThreadCrudConfiguration($crud);
        $crudValue->setFormOptions(
            newFormOptions: ['validation_groups' => ['Default', 'admin:form:new']],
            editFormOptions: ['validation_groups' => ['Default', 'admin:form:edit']]
        );

        return $crudValue;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.article.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.article.column.title');
        yield TextField::new('slug', 'admin.crud.article.column.slug')->onlyOnDetail();
        yield BooleanField::new('license', 'admin.crud.article.column.license')->setHelp("L'article est-t-il sous la licence CC-BY-NC-SA ?");
        yield TextField::new('thumbnailFile', 'admin.crud.article.column.thumbnail')
            ->setFormType(VichImageType::class)
            ->addWebpackEncoreEntries('admin:thumbnail')
            ->onlyOnForms();
        yield ImageField::new('thumbnail', 'admin.crud.article.column.thumbnail')
            ->setBasePath($this->getParameter('app.thumbnails.upload_dir'))
            ->hideOnForm();
        yield AssociationField::new('author', 'admin.crud.article.column.author')->onlyOnDetail();
        yield TextareaField::new('description', 'admin.crud.article.column.description')
            ->hideOnIndex();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.article.column.published_at')->hideOnForm();

        yield FormField::addPanel('admin.crud.section.associations');
        yield AssociationField::new('topic', 'admin.crud.article.column.topic')->setRequired(true);
        yield AssociationField::new('tags', 'admin.crud.article.column.tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin:component:tags', 'component:vue');

        yield FormField::addPanel('admin.crud.section.content');
        yield CodeEditorField::new('content', 'admin.crud.article.column.content')
            // ->setTrixEditorConfig(self::$defaultEditorConfig)
            ->setTemplatePath('admin/components/markdown.html.twig')
            ->formatValue(fn (string $value) => ParsedownFactory::create()->text($value))
            ->onlyOnDetail();
        yield MarkdownField::new('content', 'admin.crud.article.column.content')->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->defaultContentActionConfiguration($actions, Article::class);
    }
}
