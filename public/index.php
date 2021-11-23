<?php

use Bubu\Http\Reponse\Reponse;
use Bubu\Http\Session\Session;

require '../vendor/autoload.php';

define('URL', $_GET['url']);

$repository = Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()->make();
$dotenv = Dotenv\Dotenv::create($repository, '../');
$dotenv->load();
$dotenv->required(['DB_USERNAME', 'DB_PASSWORD', 'DB_NAME', 'DB_HOST', 'DB_PORT']);

if ($_ENV['LANG'] === 'auto') {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $acceptLang = explode(',', $_ENV['SUPPORTED_LANGUAGES']); 
    $lang = in_array($lang, $acceptLang) ? $lang : 'fr';
} else {
    $lang = $_ENV['LANG'];
}

$GLOBALS['lang'] = json_decode(file_get_contents("../lang/{$lang}.json"), true);

Session::set('Reponse', new Reponse());
require '../App/routes.php';

/* WARNING Code non executé après l'appel à la route */
