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

use App\Entity\Course;
use App\Enum\CourseDifficultyType;
use App\Field\Admin\MarkdownField;
use App\Service\ImageResizerService;
use App\Trait\Admin\Crud\ThreadCrudTrait;
use App\Trait\Admin\Crud\ContentCrudTrait;
use App\Service\Parsedown\ParsedownFactory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CourseCrudController extends AbstractCrudController
{
    use ThreadCrudTrait;
    use ContentCrudTrait;

    public function __construct(
        private ImageResizerService $imageResizerService,
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultThreadCrudConfiguration($crud)
                    ->setSearchFields(['title', 'description', 'topic.name', 'tags.name', 'content', 'author.username', 'author.email'])
                    ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.course.index.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.course.new.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.course.edit.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.course.details.title', [], 'admin'));
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
            ->add(ChoiceFilter::new('difficulty')
                ->setChoices(CourseDifficultyType::array())
            )
            ->add(DateTimeFilter::new('publishedAt'))
            ->add(DateTimeFilter::new('updatedAt'))
            ->add(EntityFilter::new('topic'))
            ->add(EntityFilter::new('tags'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.course.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.course.column.title')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 120]);
        yield TextField::new('slug', 'admin.crud.course.column.slug')->onlyOnDetail();
        yield BooleanField::new('license', 'admin.crud.course.column.license')->setHelp('admin.crud.course.column.license.help');
        yield BooleanField::new('private', 'admin.crud.course.column.private')->setHelp('admin.crud.course.column.private.help');
        yield IntegerField::new('views', 'admin.crud.course.column.views')->onlyOnDetail();
        yield IntegerField::new('popularity', 'admin.crud.course.column.popularity')->hideOnForm();
        yield ChoiceField::new('difficulty', 'admin.crud.course.column.difficulty')
            ->setChoices(CourseDifficultyType::cases())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => CourseDifficultyType::class,
                'data' => CourseDifficultyType::EASY,
            ])
            ->setColumns('col-md-12')
            ->onlyOnForms()
        ;
        yield ChoiceField::new('difficulty', 'admin.crud.course.column.difficulty')
            ->setChoices(CourseDifficultyType::array())
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => CourseDifficultyType::class,
            ])
            ->renderAsBadges()
            ->hideOnForm()
        ;

        yield AssociationField::new('author', 'admin.crud.course.column.author')
            ->hideOnForm();
        yield TextareaField::new('description', 'admin.crud.course.column.description')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 500])
            ->hideOnIndex();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.course.column.published_at')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.course.column.updated_at')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.associations');
        yield AssociationField::new('lessons', 'admin.crud.course.column.lessons')->hideOnForm();
        yield AssociationField::new('topic', 'admin.crud.course.column.topic')
            ->setRequired(true)
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6')
        ;
        yield AssociationField::new('tags', 'admin.crud.course.column.tags')
            ->setTemplatePath('admin/components/tags.html.twig')
            ->addWebpackEncoreEntries('admin_select_dropdown')
            ->setColumns('col-md-6')
        ;

        yield FormField::addPanel('admin.crud.section.content');
        yield CodeEditorField::new('content', 'admin.crud.course.column.content')
            ->setTemplatePath('admin/components/markdown.html.twig')
            ->addWebpackEncoreEntries('style_markdown', 'style_fonts', 'style_variables')
            ->addCssClass('markdown-style')
            ->formatValue(fn (string $value) => ParsedownFactory::create()->text($value))
            ->onlyOnDetail();
        yield MarkdownField::new('content', 'admin.crud.course.column.content')->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.course.actions.create'));

        return $this->defaultContentActionConfiguration($actions, Course::class);
    }
}
