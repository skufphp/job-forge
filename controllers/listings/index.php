<?php

declare(strict_types=1);

$config = require basePath('config/db.php');
$db = new Database($config);

$listings = $db->query('SELECT * FROM listings')->fetchAll();

loadView('listings/index', [
    'listings' => $listings
]);