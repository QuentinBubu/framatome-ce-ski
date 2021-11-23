<?php

namespace App\Migrations\System;

use Bubu\Database\Database;
use Bubu\Database\Actions\CreateColumn;


class Users
{
    public static function create(string $securityKey): void
    {
        if ($securityKey !== $_ENV['MIGRATION_SECURITY_KEY']) throw new \Exception('Invalid security key');
        Database::createTable('users')
            ->addColumn(
                Database::createColumn('id')
                    ->type(CreateColumn::BIG_INT)
                    ->size(20)
                    ->notNull()
                    ->autoIncrement()
            )
            ->addColumn(
                Database::createColumn('username')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('password')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('created_at')
                    ->type(CreateColumn::TIMESTAMP)
                    ->notNull()
                    ->defaultValue(['CURRENT_TIMESTAMP()'])
            )
            ->addColumn(
                Database::createColumn('email')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
                    ->notNull()
            )
            ->addColumn(
                Database::createColumn('email_verification_code')
                    ->type(CreateColumn::TEXT)
            )
            ->addColumn(
                Database::createColumn('email_verified_at')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
            )
            ->addColumn(
                Database::createColumn('token')
                    ->type(CreateColumn::VARCHAR)
                    ->size(255)
                    ->notNull()
            )
            ->addIndex([
                'name'    => 'primary',
                'type'    => 'primary',
                'columns' => ['id']
            ])
            ->addIndex([
                'name'    => 'mail',
                'type'    => 'unique',
                'columns' => ['email']
            ])
            ->addIndex([
                'name'    => 'token',
                'type'    => 'unique',
                'columns' => ['token']
            ])
            ->collate('utf8_general_ci')
            ->engine('InnoDB')
            ->execute();
    }
}
