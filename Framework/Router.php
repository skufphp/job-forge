<?php

declare(strict_types=1);

namespace Framework;

use App\Controllers\ErrorController;

/**
 * Класс Router для обработки маршрутов.
 *
 * Обеспечивает регистрацию и обработку HTTP-маршрутов (GET, POST, PUT, DELETE).
 * Поддерживает динамические параметры в URI и автоматическую маршрутизацию
 * запросов к соответствующим контроллерам.
 *
 * @package Framework
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
     * @return void
     */
    public function route(string $uri): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Проверяем наличие _method в POST-данных
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            // Переопределяем метод запроса
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {

            // Разделяем текущий URI на сегменты
            $uriSegments = explode('/', trim($uri, '/'));

            // Разделяем URI маршрута на сегменты
            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            // Проверяем, совпадает ли количество сегментов текущего URI с количеством сегментов URI маршрута
            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === strtoupper($requestMethod)) {

                $params = [];

                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    // Если URI не совпадает и в маршруте нет параметра, значит маршрут не подходит
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }
                    // Проверяем наличие параметра и добавляем его в массив $params
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];

                    }
                }
                if ($match) {
                    // Формируем полное имя класса контроллера с namespace
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Создаём экземпляр контроллера
                    $controllerInstance = new $controller();

                    // Вызываем метод контроллера
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}