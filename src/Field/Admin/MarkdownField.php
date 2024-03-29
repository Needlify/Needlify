<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Field\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;

final class MarkdownField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, string|false|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(TextareaType::class)
            ->setFormTypeOption('attr.class', 'field-markdown-editor-textarea')
            ->addCssClass('field-markdown-editor')
            ->addWebpackEncoreEntries('admin_editor_markdown_default')
        ;
    }
}
