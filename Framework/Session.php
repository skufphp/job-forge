<?php

declare(strict_types=1);

namespace Framework;

/**
 * Класс для управления сессиями
 *
 * Предоставляет статические методы для работы с PHP сессиями:
 * запуск, установка, получение, проверка существования и очистка данных сессии.
 *
 * @package Framework
 */
class Session
{
    /**
     * Начало сессии
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Установить пару ключ/значение в сессию
     *
     * @param string $key Ключ для установки.
     * @param mixed $value Значение для установки.
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Получить значение из сессии по ключу.
     *
     * @param string $key Ключ для получения.
     * @param mixed $default Значение по умолчанию, если ключ не найден.
     * @return mixed|null Значение из сессии или значение по умолчанию, если не найдено.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Проверить, существует ли ключ в сессии
     *
     * @param string $key Ключ для проверки.
     * @return bool Возвращает true, если ключ существует в сессии, иначе false.
     */
    public static function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Очистить данные сессии по ключу
     *
     * @param string $key Ключ для удаления из сессии.
     * @return void
     */
    public static function clear($key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Очистить все данные сессии
     *
     * @return void
     */
    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Установить flash-сообщение
     *
     * @param string $key Ключ для flash-сообщения.
     * @param string $message Текст сообщения.
     * @return void
     */
    public static function setFlashMessage(string $key, string $message): void
    {
        self::set('flash_' . $key, $message);
    }

    /**
     * Get a flash message and unset it
     *
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public static function getFlashMessage(string $key, mixed $default = null): string|null
    {
        $message = self::get('flash_' . $key, $default);
        self::clear('flash_' . $key);
        return $message;
    }
}