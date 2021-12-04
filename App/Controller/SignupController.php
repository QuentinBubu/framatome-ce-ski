<?php
namespace App\Controller;

use App\User\User;
use App\Views\Page;
use Bubu\Auth\Authorization\Authorization;
use Bubu\Database\Database;
use Bubu\Http\Session\Session;
use Bubu\Mail\Mail;

class SignupController
{
    /**
     * @return never
     */
    public static function create()
    {
        //exit(var_dump(Session::getAll()));
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
        if (User::validMail($token)) {
            (new Page)->pageMessage('Mail validé!')->pageCode(0)->show('error');
        } else {
            (new Page)->pageMessage('Erreur!')->pageCode(0)->show('error');
        }
    }

    public static function sendAgain()
    {
        $mail = Database::queryBuilder('users')
            ->select('email', 'email_verification_code')
            ->where(Database::expr()::eq('email', $_POST['email']))
            ->fetch();
        if ($mail != false && !is_null($mail['email'])) Mail::sendMail($mail['email'], 'Confirmation de votre inscription',
        <<<HTML
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                </head>
                <body>
                    <p>
                        S'il vous plaît, suivez ce lien pour valider votre demande d'inscription: 
                        <a href="{$_SERVER['SERVER_NAME']}/validEmail/{$mail['email_verification_code']}">
                           {$_SERVER['SERVER_NAME']}/validEmail?code={$mail['email_verification_code']}
                        </a>
                    </p>
                    <p>Merci!</p>
                </body>
            </html>
        HTML);
        header('Location: /login');
        exit;
    }
}
