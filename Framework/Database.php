<?php

declare(strict_types=1);

namespace Framework;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    /**
     * Экземпляр подключения к базе данных.
     * @var PDO
     */
    public $connection;

    /**
     * Инициализирует новый экземпляр класса и устанавливает соединение с базой данных.
     *
     * @param array $config Массив конфигурации, содержащий детали подключения:
     *                      'host' - хост базы данных,
     *                      'port' - порт базы данных,
     *                      'dbname' - имя базы данных,
     *                      'charset' - используемая кодировка,
     *                      'user' - имя пользователя для подключения,
     *                      'password' - пароль для подключения.
     * @return void
     * @throws PDOException Если подключение к базе данных не удалось.
     */
    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']};charset={$config['charset']}";

        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
            // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // В PHP 8.0+ включен по умолчанию
        ];

        $this->connection = new PDO($dsn, $config['user'], $config['password'], $options);
    }

    /**
     * Выполняет запрос к базе данных и возвращает подготовленное выражение после выполнения.
     *
     * @param string $query SQL-запрос для выполнения.
     * @param array $params Ассоциативный массив параметров для привязки к запросу.
     * @return PDOStatement Подготовленное выражение после выполнения запроса.
     */
    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->connection->prepare($query);

        // Привязка параметров (если есть)
        foreach ($params as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }

        $stmt->execute();
        return $stmt;
    }
}
