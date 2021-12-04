<?php
namespace App\Controller;

use App\Admin\AdminAction;
use App\User\User;
use App\Views\Page;
use Bubu\Http\Reponse\Reponse;
use Bubu\Http\Session\Session;
use Bubu\Auth\Authorization\Authorization;

class AdminController
{
    /**
     * @return never
     */
    public static function create()
    {
        //var_dump(User::getId());
        if (
            (Session::exists('authorization') && in_array('administrator', Session::get('authorization')))
            || (Session::exists('User') && Authorization::hasAuthorization(User::getId(), 'administrator'))
        ) {
            if (!in_array('administrator', Session::get('authorization'))) Session::add('authorization', 'administrator');
            (new Page)->show('admin');
        } else {
            (new Page)->show('error', (new Reponse)->reponse403());
        }
    }

    public static function store()
    {
        if (Session::exists('authorization') && in_array('administrator', Session::get('authorization'))) {
            switch ($_POST['form-name']) {
                case 'validAccount':
                    AdminAction::validAccount($_POST);
                    break;
                case 'createSortie':
                    AdminAction::createSortie($_POST);
                    break;
                case 'validSortiePeople':
                    AdminAction::validSortiePeople($_POST);
                    break;
            }
            exit(header('Location: /admin'));
        } else {
            (new Page)->show('error', (new Reponse)->reponse403());
        }
    }
}
