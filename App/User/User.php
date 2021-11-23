<?php
namespace App\User;

use Bubu\Http\Session\Session;

class User
{
    public static function getId(): int
    {
        return Session::get('User')['id'];
    }
}
