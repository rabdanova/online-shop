<?php

namespace Core;

use Controllers\UserController;
use Controllers\ProductController;
use Controllers\UserProductController;
use Controllers\OrderController;
class App
{
    private array $routes = [];
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {
            $routeMethods = $this->routes[$requestUri];
            if (isset($routeMethods[$requestMethod])) {

                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                $controller = new $class();
                $controller->$method();
            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require_once "../Views/404.php";
        }
    }

    public function addRoutes(string $route, string $routeMethod, string $className, string $method)
    {
        $this->routes[$route][$routeMethod] = [
            'class' => $className,
            'method' => $method
        ];
    }
}