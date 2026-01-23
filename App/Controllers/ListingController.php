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
    public function show(array $params): void
    {
        // Извлекаем ID из параметров
        $id = $params['id'] ?? '';

        // Подготавливаем параметры для запроса
        $params = [
            'id' => $id
        ];

        // Выполняем запрос к БД
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        // Проверяем, найдена ли запись
        if (!$listing) {
            ErrorController::notFound('Listing not found.');
            return;
        }

        // Загружаем представление с данными
        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Сохраняет данные объявления в базу данных.
     *
     * @return void
     */
    public function store(): void
    {
        $allowedFields = [
            'title', 'description', 'salary', 'requirements', 'benefits',
            'company', 'address', 'city', 'state', 'phone', 'email'
        ];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = 1;

        $newListingData = array_map('sanitize', $newListingData);
    }
}