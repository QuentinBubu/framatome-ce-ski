<?php
namespace App\User;

use Bubu\Http\Session\Session;

class User extends UserManage
{
    public static function getId(): int
    {
        return Session::get('User')['id'];
    }
}
