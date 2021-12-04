<?php
namespace App\Controller;

use App\User\User;
use App\Views\Page;
use Bubu\Http\Session\Session;
use Bubu\Auth\Authorization\Authorization;

class MembersController
{
    /**
     * @return never
     */
    public static function create()
    {
        if (Session::exists('User') && Authorization::hasAuthorization(User::getId(), 'access')) {
            (new Page)->show('members');
        } else {
            exit(header('Location: /login'));
        }
    }

    public static function store()
    {
        
    }
}
