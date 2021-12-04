<?php

namespace App\Migrations\System;

use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\Database;

class UsersSorties
{
    public static function create()
    {
        Database::CreateTable('users_sorties')
            ->addColumn(
                Database::CreateColumn('id')
                    ->type(CreateColumn::BIG_INT)
                    ->size('20')
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('missed')
                    ->type(CreateColumn::INT)
                    ->defaultValue(0)
            )->addColumn(
                Database::createColumn('registre')
                    ->type(CreateColumn::JSON)
            )->addColumn(
                Database::createColumn('pending')
                    ->type(CreateColumn::JSON)
            )
            ->foreignKey([
                'name'       => 'FK_id_users_sorties',
                'references' => 'users',
                'columns'    => ['id'],
                'foreign'    => ['id']
            ])
            ->execute();
    }
}
