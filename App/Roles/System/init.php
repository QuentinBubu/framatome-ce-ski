<?php

require '../../../vendor/autoload.php';

use Bubu\Auth\Authorization\Roles\RoleManager;

RoleManager::create('admin', ['view', 'write', 'manage', 'admin']);