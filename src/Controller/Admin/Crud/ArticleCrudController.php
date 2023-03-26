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

use App\Entity\Article;
use App\Field\Admin\MarkdownField;
use App\Service\ImageResizerService;
use App\Trait\Admin\Crud\ThreadCrudTrait;
use App\Service\Parsedown\ParsedownFactory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;

    public function __construct(
        private ImageResizerService $imageResizerService,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
                    ->setFormOptions(
                        newFormOptions: ['validation_groups' => ['Default', 'admin:form:new']],
                        editFormOptions: ['validation_groups' => ['Default', 'admin:form:edit']]
                    )
                    ->setSearchFields(['title', 'description', 'topic.name', 'tags.name', 'content', 'author.username'])
                    ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.article.index.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.article.new.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.article.edit.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.article.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title'))
            ->add(TextFilter::new('description'))
            ->add(TextFilter::new('content'))
            ->add(EntityFilter::new('author'))
            ->add(BooleanFilter::new('license'))
            ->add(BooleanFilter::new('private'))
            ->add(NumericFilter::new('views'))
            ->add(DateTimeFilter::new('publishedAt'))
            ->add(DateTimeFilter::new('updatedAt'))
            ->add(EntityFilter::new('topic'))
            ->add(EntityFilter::new('tags'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.article.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.article.column.title')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 120]);
        yield TextField::new('slug', 'admin.crud.article.column.slug')->onlyOnDetail();
        yield BooleanField::new('license', 'admin.crud.article.column.license')->setHelp('admin.crud.article.column.license.help');
        yield BooleanField::new('private', 'admin.crud.article.column.private')->setHelp('admin.crud.article.column.private.help');
        yield IntegerField::new('views', 'admin.crud.article.column.views')->onlyOnDetail();
        yield IntegerField::new('popularity', 'admin.crud.article.column.popularity')->hideOnForm();
        yield TextField::new('thumbnailFile', 'admin.crud.article.column.thumbnail')
            ->setFormType(VichImageType::class)
            ->addWebpackEncoreEntries('admin_thumbnail')
            ->onlyOnForms();
        yield ImageField::new('thumbnail', 'admin.crud.article.column.thumbnail')
            ->formatValue(fn (string $value) => $this->imageResizerService->resize($value, 500, 200))
            ->hideOnForm();
        yield AssociationField::new('author', 'admin.crud.article.column.author')
            ->hideOnForm();
        yield TextareaField::new('description', 'admin.crud.article.column.description')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 500])
            ->hideOnIndex();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.article.column.published_at')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.article.column.updated_at')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.associations');
        yield AssociationField::new('topic', 'admin.crud.article.column.topic')
            ->setRequired(true)
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6')
        ;
        yield AssociationField::new('tags', 'admin.crud.article.column.tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6')
        ;

        yield FormField::addPanel('admin.crud.section.content');
        yield CodeEditorField::new('content', 'admin.crud.article.column.content')
            ->setTemplatePath('admin/components/markdown.html.twig')
            ->addWebpackEncoreEntries('style_markdown', 'style_fonts', 'style_variables')
            ->addCssClass('markdown-style')
            ->formatValue(fn (string $value) => ParsedownFactory::create()->text($value))
            ->onlyOnDetail();
        yield MarkdownField::new('content', 'admin.crud.article.column.content')->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        $goToAction = Action::new('goTo', 'admin.crud.action.view_article')
            ->linkToUrl(fn (Article $article) => $this->urlGenerator->generate('app_article', ['slug' => $article->getSlug()]))
            ->displayIf(fn (Article $article) => !$article->isPrivate());

        $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.article.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
            ->add(Crud::PAGE_INDEX, $goToAction)
            ->add(Crud::PAGE_DETAIL, $goToAction);

        return $actions;
    }
}
