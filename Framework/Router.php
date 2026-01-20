<?php

declare(strict_types=1);

namespace Framework;

use App\Controllers\ErrorController;

/**
 * Класс Router для обработки маршрутов.
 */
class Router
{
    /**
     * Массив для хранения зарегистрированных маршрутов.
     * @var array
     */
    protected $routes = [];

    /**
     * Регистрирует маршрут с указанным методом, URI и контроллером.
     * @param string $method HTTP-метод для маршрута (например, GET, POST).
     * @param string $uri URI, связанный с маршрутом.
     * @param string $action Строка в формате 'контроллер@метод' для обработки запроса.
     * @return void
     */
    public function registerRoute(string $method, string $uri, string $action): void
    {
        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
        ];
    }

    /**
     * Регистрирует GET-маршрут с указанным URI и контроллером.
     *
     * @param string $uri URI для которого регистрируется маршрут.
     * @param string $controller Контроллер, который будет обрабатывать запрос для указанного URI.
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Регистрирует POST-маршрут с указанным URI и контроллером.
     *
     * @param string $uri URI для которого регистрируется маршрут.
     * @param string $controller Контроллер, который будет обрабатывать запрос для указанного URI.
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Регистрирует PUT-маршрут с указанным URI и контроллером.
     *
     * @param string $uri URI для которого регистрируется маршрут.
     * @param string $controller Контроллер, который будет обрабатывать запрос для указанного URI.
     * @return void
     */
    public function put(string $uri, string $controller): void
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Регистрирует DELETE-маршрут с указанным URI и контроллером.
     *
     * @param string $uri URI для которого регистрируется маршрут.
     * @param string $controller Контроллер, который будет обрабатывать запрос для указанного URI.
     * @return void
     */
    public function delete(string $uri, string $controller): void
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Маршрутизирует запрос к соответствующему контроллеру.
     *
     * @param string $uri URI запроса.
     * @param string $method HTTP-метод запроса.
     * @return void
     */
    public function route(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {

            if ($route['uri'] === $uri && $route['method'] === $method) {

                // Формируем полное имя класса с namespace
                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // Создаём экземпляр контроллера
                $controllerInstance = new $controller();

                // Вызываем метод контроллера
                $controllerInstance->$controllerMethod();
                return;
            }
        }
        ErrorController::notFound();
    }
}