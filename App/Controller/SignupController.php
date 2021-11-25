<?php
namespace App\Controller;

use App\User\User;
use App\Views\Page;
use Bubu\Auth\Authorization\Authorization;
use Bubu\Http\Session\Session;

class SignupController
{
    /**
     * @return never
     */
    public static function create()
    {
        if (Session::exists('User') && Authorization::hasAuthorization(User::getId(), 'access')) {
            header('Location: /members');
            exit;
        } else {
            (new Page)->show('signup');
        }
    }

    public static function store()
    {
        $return = User::signup($_POST['username'], $_POST['password'], $_POST['passwordConfirm'], $_POST['mail']);
        if ($return === true) {
            header('Location: /login');
            exit;
        } else {
            (new Page)->pageMessage($return)->pageCode(0)->show('error');
        }
    }

    public static function verifyMail(string $token = null)
    {
        if (is_null($token)) exit(header('Location: /'));
        var_dump(User::validMail($token));
    }
}
