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

use App\Entity\Lesson;
use App\Field\Admin\MarkdownField;
use App\Service\Parsedown\ParsedownFactory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LessonCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['publishedAt' => 'DESC'])
            ->setSearchFields(['title', 'content', 'description', 'course.title'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.lesson.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.lesson.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.lesson.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.lesson.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title'))
            ->add(TextFilter::new('content'))
            ->add(BooleanFilter::new('private'))
            ->add(NumericFilter::new('views'))
            ->add(DateTimeFilter::new('publishedAt'))
            ->add(DateTimeFilter::new('updatedAt'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.lesson.column.id')->onlyOnDetail();
        yield TextField::new('title', 'admin.crud.lesson.column.title')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 120]);
        yield TextField::new('slug', 'admin.crud.lesson.column.slug')->onlyOnDetail();
        yield BooleanField::new('private', 'admin.crud.lesson.column.private')->setHelp('admin.crud.lesson.column.private.help');
        yield IntegerField::new('views', 'admin.crud.lesson.column.views')->onlyOnDetail();
        yield TextareaField::new('description', 'admin.crud.course.column.description')
            ->setColumns(12)
            ->setFormTypeOptions(['attr.maxLength' => 500])
            ->hideOnIndex();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('publishedAt', 'admin.crud.lesson.column.published_at')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'admin.crud.lesson.column.updated_at')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.associations');
        yield AssociationField::new('course', 'admin.crud.lesson.column.course')
            ->setRequired(false)
            ->addWebpackEncoreEntries('admin_form_override_select')
            ->setColumns('col-12')
        ;

        yield FormField::addPanel('admin.crud.section.content');
        yield CodeEditorField::new('content', 'admin.crud.lesson.column.content')
            ->setTemplatePath('admin/components/markdown.html.twig')
            ->addWebpackEncoreEntries('module_markdown', 'style_fonts', 'style_variables')
            ->addCssClass('markdown-style')
            ->formatValue(fn (string $value) => ParsedownFactory::create()->text($value))
            ->onlyOnDetail();
        yield MarkdownField::new('content', 'admin.crud.lesson.column.content')->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        $goToAction = Action::new('goTo', 'admin.crud.action.view_lesson')
            ->linkToUrl(fn (Lesson $lesson) => $lesson->getCourse() ? $this->urlGenerator->generate('app_lesson', [
                    'course_slug' => $lesson->getCourse()?->getSlug(),
                    'lesson_slug' => $lesson->getSlug(),
                ]) : '')
            ->displayIf(fn (Lesson $lesson) => null !== $lesson->getCourse() && !$lesson->getCourse()->isPrivate());

        $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.lesson.actions.create'))
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
            ->add(Crud::PAGE_INDEX, $goToAction)
            ->add(Crud::PAGE_DETAIL, $goToAction);

        return $actions;
    }
}
