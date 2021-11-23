<?php
namespace App;

use Bubu\Router\Router;

$router = new Router(URL);

$router->get('/', 'Home#create');

$router->get('/login', 'Login#create');
$router->post('/login', 'Login#store');

$router->get('/signup', 'Signup#create');
$router->post('/signup', 'Signup#store');

$router->get('/admin', 'Admin#create');

$router->run();