<?php

declare(strict_types=1);

namespace App\controllers\listings;

use Framework\Database;

$config = require basePath('config/db.php');
$db = new Database($config);

$listings = $db->query('SELECT * FROM listings')->fetchAll();

loadView('listings/index', [
    'listings' => $listings
]);