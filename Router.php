<?php

declare(strict_types=1);

/**
 * Router class for handling routes.
 */
class Router
{
    protected $routes = [];

    /**
     * Registers a route with the specified method, URI, and controller.
     * @param string $method The HTTP method for the route (e.g., GET, POST).
     * @param string $uri The URI associated with the route.
     * @param string $controller The controller to handle the request.
     * @return void
     */
    public function registerRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
        ];
    }

    /**
     * Registers a GET route with the specified URI and controller.
     *
     * @param string $uri The URI for which the route is being registered.
     * @param string $controller The controller that will handle the request for the specified URI.
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Registers a POST route with the specified URI and controller.
     *
     * @param string $uri The URI for which the route is being registered.
     * @param string $controller The controller that will handle the request for the specified URI.
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Registers a PUT route with the specified URI and controller.
     *
     * @param string $uri The URI for which the route is being registered.
     * @param string $controller The controller that will handle the request for the specified URI.
     * @return void
     */
    public function put(string $uri, string $controller): void
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Registers a DELETE route with the specified URI and controller.
     *
     * @param string $uri The URI for which the route is being registered.
     * @param string $controller The controller that will handle the request for the specified URI.
     * @return void
     */
    public function delete(string $uri, string $controller): void
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Handle HTTP errors by setting the response code and loading the corresponding error view.
     *
     * @param int $httpCode The HTTP status code to send in the response. Defaults to 404.
     * @return void
     */
    public function error($httpCode = 404): void
    {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }

    /**
     * Route the request to the appropriate controller.
     *
     * @param string $uri The URI of the request.
     * @return void
     */
    public function route(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                require basePath($route['controller']);
                return;
            }
        }
        $this->error();
    }
}