<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Session;

/**
 * Middleware для авторизации пользователей.
 *
 * Проверяет права доступа к маршрутам на основе роли (auth/guest).
 */
class Authorize
{
    /**
     * Проверяет, аутентифицирован ли пользователь.
     *
     * @return bool true если пользователь залогинен, false если нет.
     */
    public function isAuthenticated(): bool
    {
        return Session::has('user');
    }

    /**
     * Обрабатывает проверку доступа к маршруту.
     *
     * @param string $role Роль доступа: 'auth' или 'guest'.
     * @return bool
     */
    public function handle(string $role): void
    {
        // Роут для гостей, но пользователь залогинен → на главную
        if ($role === 'guest' && $this->isAuthenticated()) {
            redirect('/');
        }

        // Роут для авторизованных, но пользователь НЕ залогинен → на логин
        if ($role === 'auth' && !$this->isAuthenticated()) {
            redirect('/auth/login');
        }
    }
}