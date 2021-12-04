<?php
namespace App;

use Bubu\Router\Router;

$router = new Router(URL);

$router->get('/', 'Home#create');
$router->get('/logout', 'Home#logout');

$router->get('/login', 'Login#create');
$router->post('/login', 'Login#store');

$router->post('/reserve/:id', 'User#reserve');


$router->get('/members', 'Members#create');


$router->get('/signup', 'Signup#create');
$router->post('/signup', 'Signup#store');
$router->post('/signup/sendAgain', 'Signup#sendAgain');

$router->get('/validEmail/:token', 'Signup#verifyMail');

$router->get('/admin', 'Admin#create');
$router->post('/admin', 'Admin#store');

$router->run();