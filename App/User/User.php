<?php
namespace App\User;

use Bubu\Http\Session\Session;

class User extends UserManage
{
    public static function getId(): int
    {
        return Session::get('User')['id'];
    }

    public static function getUsername(): string
    {
        return Session::get('User')['username'];
    }

    public static function getEmail(): string
    {
        return Session::get('User')['email'];
    }
}
