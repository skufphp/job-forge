<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;

/**
 * Контроллер для работы с домашней страницей и объявлением.
 */
class HomeController
{
    /**
     * Экземпляр базы данных для операций с БД
     *
     * @var Database
     */
    protected $db;

    /**
     * Инициализирует экземпляр класса, загружая конфигурацию базы данных
     * и создавая новое соединение.
     *
     * @return void
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);

    }

    /**
     * Извлекает последние объявления из базы данных и загружает домашнее представление.
     *
     * @return void
     */
    public function index(): void
    {
        $listings = $this->db->query('SELECT * FROM listings LIMIT 6')->fetchAll();

        loadView('home', [
            'listings' => $listings
        ]);
    }
}