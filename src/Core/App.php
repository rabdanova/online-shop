<?php

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'registrate',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'login',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'catalog',
            ]
        ],
        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'profile',
            ]
        ],
        '/editProfile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getEditForm',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'editProfile',
            ]
        ],
        '/add-product' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'getAddProductForm',
            ],
            'POST' => [
                'class' => 'ProductController',
                'method' => 'addProduct',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'cart',
            ]
        ]
    ];
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $routeMethods = $this->routes[$requestUri];

        $handler = $routeMethods[$requestMethod];

        $class = $handler['class'];
        $method = $handler['method'];

        require_once "../Controllers/$class.php";
        $controller = new $class();
        $controller->$method();
    }
}