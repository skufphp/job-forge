<?php

declare(strict_types=1);

namespace Framework;

use Framework\Session;

/**
 * Класс для управления авторизацией и проверкой прав доступа
 */
class Authorization
{
    /**
     * Проверить, является ли текущий авторизованный пользователь владельцем ресурса
     *
     * @param int $resourceID Идентификатор ресурса для проверки владения
     * @return bool Возвращает true, если текущий пользователь является владельцем ресурса, иначе false
     */
    public static function isOwner(int $resourceID): bool
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            return $sessionUser['id'] === $resourceID;
        }
        return false;
    }
}