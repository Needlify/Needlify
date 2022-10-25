<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud\Traits;

use App\Entity\Tag;
use App\Entity\Topic;
use App\Entity\Classifier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

trait ClassifierCrudTrait
{
    public function defaultFieldConfiguration(string $pageName, string $classifierFqcn): iterable
    {
        yield FormField::addPanel('Essential');
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('name');
        yield TextField::new('slug')->onlyOnDetail();

        yield FormField::addPanel('Date Details')->hideOnForm();
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('lastUseAt')->hideOnForm();

        yield FormField::addPanel('Associations')->hideOnForm();
        yield AssociationField::new('publications')->hideOnForm();
    }

    public function defaultActionConfiguration(Actions $actions, string $classifierFqcn): Actions
    {
        if (Tag::class === $classifierFqcn) {
            $actionLabel = 'View tag page';
            $actionRouteName = 'app_tag';
        } elseif (Topic::class === $classifierFqcn) {
            $actionLabel = 'View topic page';
            $actionRouteName = 'app_topic';
        } else {
            $actionLabel = 'View page';
            $actionRouteName = 'app_home';
        }

        $goToAction = Action::new('goTo', $actionLabel)
            ->linkToRoute($actionRouteName, function (Classifier $tag): array {
                return [
                    'slug' => $tag->getSlug(),
                ];
            });

        $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('Details'))

            ->add(Crud::PAGE_INDEX, $goToAction)

            ->add(Crud::PAGE_DETAIL, $goToAction)
            ->update(Crud::PAGE_DETAIL, 'goTo', fn (Action $action) => $action->setCssClass('btn btn-secondary'))

            // ->update(Crud::PAGE_DETAIL, Action::DELETE, fn (Action $action) => $action->setLabel(''))
            // ->update(Crud::PAGE_DETAIL, Action::INDEX, fn (Action $action) => $action->setLabel('')->setIcon('fas fa-list'))
            // ->update(Crud::PAGE_DETAIL, Action::EDIT, fn (Action $action) => $action->setLabel('')->setIcon('fas fa-edit'))
            // ->update(Crud::PAGE_DETAIL, 'goTo', fn (Action $action) => $action->setLabel('')->setIcon('fas fa-eye'))

            // ->reorder(Crud::PAGE_DETAIL, [Action::DELETE, 'goTo', Action::INDEX, Action::EDIT])

        ;

        if (Tag::class === $classifierFqcn) {
            $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }
}
