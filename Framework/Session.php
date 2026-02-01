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
     * Set a session key/value pair.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to set.
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value by key.
     *
     * @param string $key The key to retrieve.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed|null The session value or null if not found.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     *
     * @param string $key
     * @return bool
     */
    public static function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear session by key
     *
     * @param string $key
     * @return void
     */
    public static function clear($key): void
    {
        if (!isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session data
     *
     * @return void
     */
    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }
}