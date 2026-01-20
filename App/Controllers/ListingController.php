<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;

class ListingController
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
     * Отображает все объявления.
     *
     * @return void
     */
    public function index(): void
    {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    /**
     * Отображает форму создания объявления.
     *
     * @return void
     */
    public function create(): void
    {
        loadView('listings/create');
    }

    /**
     * Отображает детали конкретного объявления.
     *
     * @return void
     */
    public function show(): void
    {
        $id = $_GET['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        loadView('listings/show', [
            'listing' => $listing
        ]);

    }
}