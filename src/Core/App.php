<?php

namespace Core;

use Controllers\UserController;
use Controllers\ProductController;
use Controllers\UserProductController;
use Controllers\OrderController;
class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ]
        ],
        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'logout',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'catalog',
            ]
        ],
        '/profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'profile',
            ]
        ],
        '/editProfile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getEditForm',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ]
        ],
        '/add-product' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getAddProductForm',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'addProduct',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => UserProductController::class,
                'method' => 'cart',
            ]
        ],
        '/create-order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getCheckoutForm',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'handleCheckout',
            ]
        ],
        '/my-orders' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getUserOrders',
            ]
        ]
    ];
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
}