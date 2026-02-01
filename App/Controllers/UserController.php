<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Session;
use Framework\Database;
use Framework\Validation;

/**
 * Контроллер для работы с пользователями.
 */
class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Отображает страницу входа в систему
     *
     * @return void
     */
    public function login(): void
    {
        loadView('users/login');
    }

    /**
     * Отображает страницу регистрации
     *
     * @return void
     */
    public function create(): void
    {
        loadView('users/create');
    }

    /**
     * Сохраняет нового пользователя в базе данных
     *
     * @return void
     */
    public function store(): void
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirmation = $_POST['password_confirmation'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';

        $errors = [];

        // Проверка email
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        // Проверка имени (от 2 до 50 символов)
        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        // Проверка пароля (минимум 6 символов)
        if (!Validation::string($password, 8, 20)) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        // Проверка совпадения паролей
        if (!Validation::match($password, $password_confirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            // Есть ошибки — показываем форму снова с ошибками
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        // Проверка существования email
        $params = [
            'email' => $email
        ];

        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();

        if ($user) {
            $errors['email'] = 'Email already exists';
            loadView('users/create', [
                'errors' => $errors,
            ]);
            exit;
        }

        // Создание аккаунта пользователя
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'city' => $city,
            'state' => $state
        ];

        $this->db->query(
            "INSERT INTO users (name, email, city, state, password) 
             VALUES (:name, :email, :city, :state, :password)", $params
        );

        // Get new user ID
        $userID = $this->db->connection->lastInsertId();

        Session::set('user', [
            'id' => $userID,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);

        redirect('/');
    }
}