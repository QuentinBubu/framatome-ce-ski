<?php

namespace App\Migrations\System;

use Bubu\Database\Database;
use Bubu\Database\Actions\CreateColumn;

class Sorties
{
    public static function create(string $securityKey): void
    {
        if ($securityKey !== $_ENV['MIGRATION_SECURITY_KEY']) throw new \Exception('Invalid security key');
        Database::createTable('sorties')
            ->addColumn(
                Database::createColumn('id')
                    ->type(CreateColumn::BIG_INT)
                    ->size(20)
                    ->notNull()
                    ->autoIncrement()
            )
            ->addColumn(
                Database::createColumn('name')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('date')
                    ->type(CreateColumn::DATE)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('created_at')
                    ->type(CreateColumn::TIMESTAMP)
                    ->notNull()
                    ->defaultValue(['CURRENT_TIMESTAMP()'])
            )
            ->addColumn(
                Database::createColumn('people_waiting')
                    ->type(CreateColumn::JSON)
            )
            ->addColumn(
                Database::createColumn('participant')
                    ->type(CreateColumn::JSON)
            )
            ->addColumn(
                Database::createColumn('available_place')
                    ->type(CreateColumn::INT)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('temp_available_place')
                    ->type(CreateColumn::INT)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('comments')
                    ->type(CreateColumn::TEXT)
            )
            ->addIndex([
                'name'    => 'primary',
                'type'    => 'primary',
                'columns' => ['sortie_id']
            ])
            ->collate('utf8_general_ci')
            ->engine('InnoDB')
            ->execute();
    }
}
