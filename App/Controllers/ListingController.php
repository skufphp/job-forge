<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
     * Отображает список всех объявлений.
     *
     * Метод выполняет запрос к базе данных для получения всех объявлений
     * и загружает представление со списком объявлений.
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
     * Метод извлекает ID объявления из параметров маршрута, выполняет запрос к базе данных
     * для получения информации об объявлении. Если объявление не найдено, вызывается
     * страница ошибки 404. При успешном нахождении загружается представление с деталями объявления.
     *
     * @param array $params Массив параметров маршрута, содержащий 'id' объявления
     * @return void
     */
    public function show(array $params): void
    {
        // Извлекаем ID из параметров
        $id = $params['id'] ?? '';

        // Подготавливаем параметры для запроса
        $queryParams = [
            'id' => $id
        ];

        // Выполняем запрос к БД
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

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
     * Метод получает данные из POST-запроса, фильтрует их по разрешенным полям,
     * выполняет валидацию обязательных полей и сохраняет объявление в базу данных.
     * При наличии ошибок валидации перезагружает форму создания с сообщениями об ошибках.
     * После успешного сохранения перенаправляет пользователя на страницу списка объявлений.
     *
     * @return void
     */
    public function store(): void
    {
        $allowedFields = [
            'title', 'description', 'salary', 'requirements', 'benefits',
            'company', 'address', 'city', 'state', 'phone', 'email', 'tags'
        ];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = 1;

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {

            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        if (!empty($errors)) {
            // Перезагрузка представления с ошибками
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {

            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                // Заменяет пустые строки на null
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                // Создает параметры для prepared statement
                $values[] = ':' . $field;
            }
            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            $this->db->query($query, $newListingData);

            redirect('/listings');
        }
    }

    /**
     * Удаляет объявление из базы данных.
     *
     * Метод извлекает ID объявления из параметров маршрута, выполняет проверку существования
     * объявления в базе данных. Если объявление не найдено, вызывается страница ошибки 404.
     * При успешной проверке выполняется удаление объявления из базы данных и перенаправление
     * пользователя на страницу списка объявлений.
     *
     * @param array $params Массив параметров маршрута, содержащий 'id' объявления
     * @return void
     */
    public function destroy(array $params): void
    {
        // Извлекаем ID из параметров
        $id = $params['id'] ?? '';

        // Подготавливаем параметры для запроса
        $queryParams = [
            'id' => $id
        ];

        // Проверяем, существует ли объявление
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        // Если не существует — показываем 404
        if (!$listing) {
            ErrorController::notFound('Listing not found.');
        }

        // Удаляем объявление
        $this->db->query("DELETE FROM listings WHERE id = :id", $queryParams);

        // Установка flash-сообщения
        $_SESSION['success_message'] = 'Listing deleted successfully.';

        // Перенаправляем на список объявлений
        redirect('/listings');
    }

    /**
     * Отображает форму редактирования объявления.
     *
     * Метод извлекает ID объявления из параметров маршрута, выполняет запрос к базе данных
     * для получения информации об объявлении. Если объявление не найдено, вызывается
     * страница ошибки 404. При успешном нахождении загружается представление формы редактирования с данными объявления.
     *
     * @param array $params Массив параметров маршрута, содержащий 'id' объявления
     * @return void
     */
    public function edit(array $params): void
    {
        // Извлекаем ID из параметров
        $id = $params['id'] ?? '';

        // Подготавливаем параметры для запроса
        $queryParams = [
            'id' => $id
        ];

        // Выполняем запрос к БД
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        // Проверяем, найдена ли запись
        if (!$listing) {
            ErrorController::notFound('Listing not found.');
            return;
        }

        // Загружаем представление с данными
        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Обновляет данные объявления в базе данных.
     *
     * Метод извлекает ID объявления из параметров маршрута, проверяет существование объявления
     * в базе данных. Получает данные из POST-запроса, фильтрует их по разрешенным полям,
     * выполняет валидацию обязательных полей и сохраняет изменения в базу данных.
     * При наличии ошибок валидации перезагружает форму редактирования с сообщениями об ошибках.
     * После успешного обновления перенаправляет пользователя на страницу просмотра объявления.
     *
     * @param array $params Массив параметров маршрута, содержащий 'id' объявления
     * @return void
     */
    public function update($params)
    {
        // Извлекаем ID из параметров
        $id = $params['id'] ?? '';

        // Подготавливаем параметры для запроса
        $queryParams = [
            'id' => $id
        ];

        // Выполняем запрос к БД
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        // Проверяем, найдена ли запись
        if (!$listing) {
            ErrorController::notFound('Listing not found.');
            return;
        }

        $allowedFields = [
            'title', 'description', 'salary', 'requirements', 'benefits',
            'company', 'address', 'city', 'state', 'phone', 'email', 'tags'
        ];

        $updateValues = [];
        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) && !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        if (!empty($errors)) {
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => $listing
            ]);
            exit;
        } else {
            // Submit the updated listing data to the database
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "$field = :$field";
            }

            $updateFields = implode(', ', $updateFields);
            $updateQuery = "UPDATE listings SET {$updateFields} WHERE id = :id";
            $updateValues['id'] = $id;

            $this->db->query($updateQuery, $updateValues);

            $_SESSION['success_message'] = 'Listing updated successfully.';

            redirect('/listings/' . $id);
        }
    }
}