<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * https:// knplabs.com/en/blog/how-to-map-a-php-enum-with-doctrine-in-a-symfony-project.
 */
abstract class AbstractEnumType extends Type
{
    abstract public function getEnum(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $enum = $this->getEnum();
        $cases = array_map(
            fn ($enumItem) => "'{$enumItem->value}'",
            $enum::cases()
        );

        return sprintf('ENUM(%s)', implode(', ', $cases));
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $enumClass = $this->getEnum();

        return $enumClass::from($value);
    }

    public function convertToDatabaseValue($enum, AbstractPlatform $platform)
    {
        return $enum->value;
    }
}
