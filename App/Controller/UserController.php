<?php
namespace App\Controller;

use App\User\UserAction;
use App\Views\Page;
use Bubu\Http\Session\Session;

class UserController
{
    /**
     * @return never
     */
    public static function reserve(int $id)
    {
        if (Session::exists('authorization') && in_array('access', Session::get('authorization'))) {
            UserAction::reserve($id, $_POST);
            header('Location: /members');
            exit;
        } else {
            exit(header('Location: /login'));
        }
    }
}