<?php
namespace App\User;

use Bubu\Mail\Mail;
use Bubu\Database\Database;
use Bubu\Http\Session\Session;

class UserManage
{

    /**
     * signup
     * @param string $username
     * @param string $password
     * @param string $passwordConfirm
     * @param string $email
     * 
     * @return bool|string
     */
    public static function signup(
        string $username,
        string $password,
        string $passwordConfirm,
        string $mail
    ) {
        $mailFetch = Database::queryBuilder('users')
        ->select('mail')
        ->where(
            Database::expr()->eq('mail', 'mail', $mail)
        )
        ->fetch();

        if ($mailFetch !== false && count($mailFetch) !== 0) {
            return $GLOBALS['lang']['existing-email'];
        } elseif (
            $password !== $passwordConfirm
        ) {
            return $GLOBALS['lang']['not-same-password'];
        } elseif (
            strlen($password) <= 10
            || strlen($password) >= 30
        ) {
            return $GLOBALS['lang']['password-length'];
        } elseif (
            strlen($username) <= 3
        ) {
            return $GLOBALS['lang']['username-length'];
        }

        $emailCode = bin2hex(random_bytes(10));

        Database::queryBuilder('users')
            ->insert([
                'username' => $username,
                'email'    => $mail,
                'password' => password_hash($password, constant($_ENV['HASH_ALGO'])),
                'token'    => bin2hex(random_bytes(30)),
                'email_verification_code' => $emailCode,
            ])
            ->execute();

        Mail::sendMail(
            $mail,
            'Confirmation de votre inscription',
            <<<HTML
                <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                    </head>
                    <body>
                        <p>
                            S'il vous plaît, suivez ce lien pour valider votre demande d'inscription: 
                            <a href="{$_SERVER['SERVER_NAME']}/validEmail?code={$emailCode}">
                               {$_SERVER['SERVER_NAME']}/validEmail?code={$emailCode}
                            </a>
                        </p>
                        <p>Merci!</p>
                    </body>
                </html>
            HTML
        );

        return true;

    }

    /**
     * login
     * @param string $mail
     * @param string $password
     * @param bool $keepSession
     * 
     * @return bool|string
     */
    public static function login(
        string $mail,
        string $password,
        bool $keepSession = false
    ): mixed {
        $dbData = Database::queryBuilder('users')
            ->select('id', 'password', 'email_verified_at', 'token')
            ->where(
                Database::expr()->eq('mail', 'mail', $mail)
            )
            ->fetch();

        if ($dbData === false || count($dbData) === 0) {
            return 'Compte introuvable';
        } elseif (!password_verify($password, $dbData['password'])) {
            return $GLOBALS['lang']['incorrect-password'];
        } elseif (is_null($dbData['email_verified_at'])) {
            return $GLOBALS['lang']['email-not-verified'];
        } else {
            Session::set('token', $dbData['token']);
            Session::set('User', []);
            Session::push('User', $dbData['id']);
            if ($keepSession) {
                Session::changeSessionLifetime($_ENV['SESSION_KEEP_CONNECT']);
            }
            return true;
        }
    }
}