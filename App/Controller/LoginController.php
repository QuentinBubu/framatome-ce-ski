<?php
namespace App\Controller;

use App\User\User;
use App\Views\Page;
use Bubu\Auth\Authorization\Authorization;
use Bubu\Flash\Flash;
use Bubu\Http\Reponse\Reponse;
use Bubu\Http\Session\Session;

class LoginController
{
    /**
     * @return never
     */
    public static function create()
    {
        (new Page)->show('login');
    }

    public static function store()
    {
        $return = User::login($_POST['username'], $_POST['password'], isset($_POST['keepConnexion']));
        if ($return === true) {
            if (Authorization::hasAuthorization(User::getId(), 'access')) {
                Session::set('authorization', ['access']);
                header('Location: /members');
                exit;
            } else {
                Flash::alert($GLOBALS['lang']['unauthorize']);
                (new Page)->show('error', (new Reponse)->reponse403());
            }
        } else {
            Flash::alert($GLOBALS['lang']['unauthorize']);
            (new Page)->pageMessage($return)->show('error');
        }
    }
}
