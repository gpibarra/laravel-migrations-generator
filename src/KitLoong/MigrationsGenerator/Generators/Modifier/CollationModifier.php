<?php

namespace KitLoong\MigrationsGenerator\Generators\Modifier;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use KitLoong\MigrationsGenerator\Generators\Blueprint\ColumnMethod;
use KitLoong\MigrationsGenerator\Generators\MigrationConstants\Method\ColumnModifier;
use KitLoong\MigrationsGenerator\MigrationsGeneratorSetting;

class CollationModifier
{
    public function chainCollation(Table $table, ColumnMethod $method, string $type, Column $column): ColumnMethod
    {
        if (!app(MigrationsGeneratorSetting::class)->isUseDBCollation()) {
            return $method;
        }

        // collation is not set in PgSQL
        $defaultCollation = $table->getOptions()['collation'] ?? '';

        $collation = $column->getPlatformOptions()['collation'] ?? null;
        if ($collation !== null && $collation !== $defaultCollation) {
            $method->chain(ColumnModifier::COLLATION, $collation);
        }

        return $method;
    }
}