<?php
namespace App\Controller;

use App\Views\Page;
use Bubu\Http\Session\Session;

class HomeController
{
    /**
     * @return never
     */
    public static function create()
    {
        (new Page)->show('home');
    }

    public static function logout()
    {
        Session::destroy();
        header('Location: /');
        exit;
    }
}
