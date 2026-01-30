<?php

declare(strict_types=1);

/**
 * Вспомогательные функции для приложения
 */

/**
 * Строит путь к базовому каталогу, добавляя указанный относительный путь.
 *
 * @param string $path Относительный путь для добавления к базовому каталогу. По умолчанию пустая строка.
 * @return string Абсолютный путь, полученный путем объединения базового каталога с предоставленным относительным путем.
 */
function basePath(string $path = ''): string
{
    return __DIR__ . '/' . $path;
}

/**
 * Загружает файл представления по имени.
 *
 * @param string $name Имя представления для загрузки.
 * @param array $data Данные для передачи в представление.
 * @return void
 */
function loadView(string $name, array $data = []): void
{
    $viewPath = basePath("App/views/$name.view.php");

    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View $name not found.";
    }
}

/**
 * Загружает файл частичного представления по имени.
 *
 * @param string $name Имя частичного представления для загрузки.
 * @return void
 */
function loadPartials(string $name, $data = []): void
{
    $partialPath = basePath("App/views/partials/$name.php");

    if (file_exists($partialPath)) {
        extract($data);
        require $partialPath;
    } else {
        echo "Partial $name not found.";
    }
}

/**
 * Выводит человекочитаемое представление данного значения.
 *
 * @param mixed $value Значение для проверки и вывода.
 * @return void
 */
function inspect(mixed $value): void
{
    echo '<pre>' . var_dump($value) . '</pre>';
}

/**
 * Выводит человекочитаемое представление данного значения и завершает выполнение скрипта.
 *
 * @param mixed $value Значение для проверки и вывода.
 * @return never
 */
function inspectAndExit(mixed $value): never
{
    echo '<pre>' . var_dump($value) . '</pre>';
    exit(1);
}

/**
 * Форматирует зарплату в денежный формат с символом доллара.
 *
 * @param mixed $salary Значение зарплаты для форматирования.
 * @return string Отформатированная строка с символом доллара и числовым значением зарплаты с разделителями тысяч.
 */
function formatSalary($salary)
{
    return '$' . number_format((int)$salary);
}

/**
 * Очищает данные от потенциально опасных символов.
 *
 * @param string $dirty Исходная строка для очистки.
 * @return string Очищенная строка, безопасная для вывода в HTML.
 */
function sanitize(string $dirty): string
{
    return htmlspecialchars(trim($dirty), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Перенаправляет пользователя на указанный URL.
 *
 * @param string $url URL-адрес для перенаправления.
 * @return void
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}
