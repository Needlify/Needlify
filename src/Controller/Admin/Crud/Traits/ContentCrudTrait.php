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
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

trait ContentCrudTrait
{
    public function defaultContentActionConfiguration(Actions $actions, string $classifierFqcn): Actions
    {
        if (Tag::class === $classifierFqcn) {
            $actionLabel = 'View tag page';
            $actionRouteName = 'app_tag';
        } elseif (Topic::class === $classifierFqcn) {
            $actionLabel = 'View topic page';
            $actionRouteName = 'app_topic';
        } elseif (Article::class === $classifierFqcn) {
            $actionLabel = 'View article page';
            $actionRouteName = 'app_article';
        } else {
            $actionLabel = 'View page';
            $actionRouteName = 'app_home';
        }

        /** @var Classifier|Article $content */
        $goToAction = Action::new('goTo', $actionLabel)
            ->linkToRoute($actionRouteName, function ($content): array {
                return [
                    'slug' => $content->getSlug(),
                ];
            });

        $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->add(Crud::PAGE_INDEX, $goToAction)

            ->add(Crud::PAGE_DETAIL, $goToAction)
            ->update(Crud::PAGE_DETAIL, 'goTo', fn (Action $action) => $action->setCssClass('btn btn-secondary'))

            // ->update(Crud::PAGE_DETAIL, Action::DELETE, fn (Action $action) => $action->setLabel(''))
            // ->update(Crud::PAGE_DETAIL, Action::INDEX, fn (Action $action) => $action->setLabel('')->setIcon('fas fa-list'))
            // ->update(Crud::PAGE_DETAIL, Action::EDIT, fn (Action $action) => $action->setLabel('')->setIcon('fas fa-edit'))
            // ->update(Crud::PAGE_DETAIL, 'goTo', fn (Action $action) => $action->setLabel('')->setIcon('fas fa-eye'))

            // ->reorder(Crud::PAGE_DETAIL, [Action::DELETE, 'goTo', Action::INDEX, Action::EDIT])

        ;

        return $actions;
    }
}
