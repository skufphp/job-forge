<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Session;
use Framework\Database;
use Framework\Validation;

/**
 * Контроллер для работы с пользователями.
 *
 * Обрабатывает регистрацию, аутентификацию и управление пользователями.
 *
 * @package App\Controllers
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
     * Выполняет выход из системы
     *
     * @return void
     */
    public function logout()
    {
        // Получаем реальное имя cookie сессии (не hardcode!)
        $cookieName = session_name();  // Вернёт 'PHPSESSID' или кастомное имя

        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie($cookieName, '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
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
     * Обрабатывает данные формы регистрации, выполняет валидацию полей (имя, email, пароль, город, штат),
     * проверяет уникальность email, создает нового пользователя с хешированным паролем,
     * автоматически авторизует пользователя после успешной регистрации и перенаправляет на главную страницу.
     * При наличии ошибок валидации показывает форму регистрации с сообщениями об ошибках.
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

        // Проверка пароля (минимум 8 символов)
        if (!Validation::string($password, 6, 15)) {
            $errors['password'] = 'Password must be at least 6 characters';
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

        // Получение ID нового пользователя
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

    /**
     * Аутентификация пользователя
     *
     * Проверяет email и пароль пользователя, выполняет валидацию данных,
     * сверяет учетные данные с базой данных и создает сессию при успешной аутентификации.
     * При наличии ошибок валидации или неверных учетных данных показывает форму входа с сообщениями об ошибках.
     *
     * @return void
     */
    public function authenticate(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];

        // Проверка email
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        // Проверка пароля (минимум 8 символов)
        if (!Validation::string($password, 6, 15)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            // Есть ошибки — показываем форму снова с ошибками
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Проверка существования email
        $params = [
            'email' => $email
        ];

        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect email or password';
            loadView('users/login', [
                'errors' => $errors,
            ]);
            exit;
        }

        // Проверка пароля
        if (!password_verify($password, $user->password)) {
            $errors['password'] = 'Incorrect email or password';
            loadView('users/login', [
                'errors' => $errors,
            ]);
            exit;
        }

        // Сохранение данных пользователя в сессию
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);

        redirect('/');
    }
}