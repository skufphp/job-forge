<?php

declare(strict_types=1);

namespace Framework;

class Validation
{
    /**
     * Проверяет, соответствует ли заданная строка ограничениям по длине.
     *
     * @param string $value Строка для проверки.
     * @param int $min Минимально допустимая длина строки. По умолчанию 1.
     * @param int|float $max Максимально допустимая длина строки. По умолчанию INF.
     * @return bool Возвращает true, если длина строки находится в заданном диапазоне, иначе false.
     */
    public static function string(string $value, int $min = 1, int|float $max = INF): bool
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            return $length >= $min && $length <= $max;
        }
        return false;
    }

    /**
     * Проверяет адрес электронной почты.
     *
     * @param string $value Адрес электронной почты для проверки.
     * @return string|false Возвращает отфильтрованный адрес электронной почты, если он валиден, или false в противном случае.
     */
    public static function email(string $value): string|false
    {
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Сравнивает два обрезанных строковых значения на равенство.
     *
     * @param string $value1 Первая строка для сравнения.
     * @param string $value2 Вторая строка для сравнения.
     * @return bool Возвращает true, если обрезанные строки идентичны, иначе false.
     */
    public static function match(string $value1, string $value2): bool
    {
        $value1 = trim($value1);
        $value2 = trim($value2);
        return $value1 === $value2;
    }

}