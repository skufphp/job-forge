<?php

declare(strict_types=1);

require '../helpers.php';
require basePath('Router.php');
require basePath('Database.php');

// Создание экземпляра маршрутизатора для обработки HTTP-запросов
$router = new Router();

// Получение и загрузка маршрутов из файла routes.php
$routes = require basePath('routes.php');

// Получение текущего URI и HTTP-метода запроса из суперглобального массива $_SERVER
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Маршрутизация запроса - поиск и выполнение контроллера соответствующего URI и HTTP-методу
try {
    $router->route($uri, $method);
} catch (PDOException $e) {
    http_response_code(500);
    exit("Ошибка базы данных:" . $e->getMessage());
} catch (Exception $e) {
    http_response_code(500);
    exit("Неизвестная ошибка:" . $e->getMessage());
}
