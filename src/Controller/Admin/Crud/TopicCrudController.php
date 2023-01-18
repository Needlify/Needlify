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

use App\Entity\Topic;
use App\Trait\Admin\Crud\ContentCrudTrait;
use App\Trait\Admin\Crud\ClassifierCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TopicCrudController extends AbstractCrudController
{
    use ClassifierCrudTrait;
    use ContentCrudTrait;

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $this->defaultClassifierCrudConfiguration($crud)
                    ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.topic.index.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.topic.new.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.topic.edit.title', [], 'admin'))
                    ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.topic.details.title', [], 'admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $this->defaultFilterConfiguration($filters);
    }

    public function configureFields(string $pageName): iterable
    {
        return $this->defaultClassifierFieldConfiguration($pageName, Topic::class);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.topic.actions.create'));

        return $this->defaultContentActionConfiguration($actions, Topic::class);
    }
}
