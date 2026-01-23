<?php

declare(strict_types=1);

namespace App\Controllers;

class ErrorController
{
    /**
     * Обрабатывает случай, когда запрашиваемый ресурс не найден, возвращая 404 ответ.
     *
     * @param string $message Сообщение об ошибке для отображения. По умолчанию 'Ресурс не найден'.
     * @return void
     */
    public static function notFound($message = 'Ресурс не найден'): void
    {
        // Устанавливаем HTTP-код 404
        http_response_code(404);

        // Загружаем представление ошибки
        loadView('error', [
            'status' => '404',
            'message' => $message,
        ]);
    }

    /**
     * Отправляет HTTP-ответ 403 Forbidden и загружает представление ошибки с указанным сообщением.
     *
     * @param string $message Пользовательское сообщение для отображения в представлении ошибки. По умолчанию 'Вы не авторизованы для просмотра этого ресурса'.
     * @return void
     */
    public static function unauthorized($message = 'Вы не авторизованы для просмотра этого ресурса'): void
    {
        // Устанавливаем HTTP-код 404
        http_response_code(403);

        // Загружаем представление ошибки
        loadView('error', [
            'status' => '403',
            'message' => $message,
        ]);
    }
}