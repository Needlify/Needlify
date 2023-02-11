<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine\EventListener;

use App\Doctrine\Type\AbstractEnumType;

class BannerEnumTypeMigrationListener
{
    public function postGenerateSchema(\Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs $eventArgs)
    {
        $columns = [];

        foreach ($eventArgs->getSchema()->getTables() as $table) {
            foreach ($table->getColumns() as $column) {
                if ($column->getType() instanceof AbstractEnumType) {
                    $columns[] = $column;
                }
            }
        }

        /** @var \Doctrine\DBAL\Schema\Column $column */
        foreach ($columns as $column) {
            /** @var AbstractEnumType $type */
            $type = $column->getType();
            $enum = $type->getEnum();

            $cases = array_map(
                fn ($enumItem) => "'{$enumItem->value}'",
                $enum::cases()
            );

            $hash = md5(implode(',', $cases));

            $column->setComment(trim(sprintf(
                '%s (DC2Enum:%s)',
                $column->getComment(),
                $hash
            )));
        }
    }
}
