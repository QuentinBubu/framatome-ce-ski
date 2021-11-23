<?php
namespace App;

use Bubu\Router\Router;

$router = new Router(URL);

$router->get('/', 'Home#create');
$router->get('/admin', 'Admin#create');

$router->run();