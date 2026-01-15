<?php

declare(strict_types=1);

require '../helpers.php';
require basePath('Router.php');

$router = new Router();
$routes = require basePath('routes.php');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);