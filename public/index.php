<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/../vendor/autoload.php';
use Framework\Router;
use Framework\Database;

require '../helpers.php';

// Создание экземпляра маршрутизатора для обработки HTTP-запросов
$router = new Router();

// Получение и загрузка маршрутов из файла routes.php
require basePath('routes.php');

// Получение текущего URI и HTTP-метода запроса из суперглобального массива $_SERVER
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Маршрутизация запроса - поиск и выполнение контроллера соответствующего URI и HTTP-методу
try {
    $router->route($uri);
} catch (PDOException $e) {
    http_response_code(500);
    exit("Ошибка базы данных:" . $e->getMessage());
} catch (Exception $e) {
    http_response_code(500);
    exit("Неизвестная ошибка:" . $e->getMessage());
}
