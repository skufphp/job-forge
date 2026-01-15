<?php

declare(strict_types=1);

require '../helpers.php';

$uri = $_SERVER['REQUEST_URI'];

require basePath('router.php');
