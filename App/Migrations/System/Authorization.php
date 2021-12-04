<?php

namespace App\Migrations\System;

use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\Database;

class Authorization
{
    public static function create(string $securityKey): void
    {
        if ($securityKey !== $_ENV['MIGRATION_SECURITY_KEY']) throw new \Exception('Invalid security key');
        self::createAuthorization([
            // authorization list
            'access', 'create', 'read', 'comment', 'moderator', 'administrator' // etc
        ]);
    }

    private static function createAuthorization(array $authorizationName)
    {
        $db = Database::CreateTable('authorization')
        ->addColumn(
            Database::CreateColumn('id')
                ->type(CreateColumn::BIG_INT)
                ->size('20')
                ->notNull()
        )
        ->foreignKey([
            'name'       => 'FK_id',
            'references' => 'users',
            'columns'    => ['id'],
            'foreign'    => ['id']
        ]);

        foreach ($authorizationName as $value) {
            $db->addColumn(
                Database::createColumn($value)
                    ->type(CreateColumn::TINY_INT)
                    ->size(1)
                    ->defaultValue([0])
            );
        }
        $db->addColumn(
            Database::createColumn('reserve')
                ->type(CreateColumn::TINY_INT)
                ->size(1)
                ->defaultValue([1])
        );
        $db->execute();
    }
}
