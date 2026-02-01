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
        if (!isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
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
}