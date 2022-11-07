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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
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
        $crudValue = $this->defaultThreadCrudConfiguration($crud);
        $crudValue->setFormOptions(
            newFormOptions: ['validation_groups' => ['Default', 'admin:form:new']],
            editFormOptions: ['validation_groups' => ['Default', 'admin:form:edit']]
        );

        return $crudValue;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('title');
        yield TextField::new('thumbnailFile')
            ->setFormType(VichImageType::class)
            ->addWebpackEncoreEntries('admin:thumbnail')
            ->onlyOnForms();
        yield ImageField::new('thumbnail')
            ->setBasePath($this->getParameter('app.thumbnails.upload_dir'))
            ->hideOnForm();
        yield AssociationField::new('author')->onlyOnDetail();
        yield TextField::new('slug')->onlyOnDetail();
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield TextEditorField::new('description')
            ->setTrixEditorConfig(self::$defaultEditorConfig)
            ->onlyOnIndex();

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('publishedAt')->hideOnForm();

        yield FormField::addPanel('Associations');
        yield AssociationField::new('topic')->setRequired(true);
        yield AssociationField::new('tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin:component:tags', 'component:vue');

        yield FormField::addPanel('Content');
        yield CodeEditorField::new('content')
            // ->setTrixEditorConfig(self::$defaultEditorConfig)
            ->setTemplatePath('admin/components/markdown.html.twig')
            ->formatValue(fn (string $value) => ParsedownFactory::create()->text($value))
            ->hideOnForm();
        yield MarkdownField::new('content')->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->defaultContentActionConfiguration($actions, Article::class);
    }
}
