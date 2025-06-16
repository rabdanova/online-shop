<?php

use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Controllers\UserProductController;

$autoload = function (string $className) {
    $path = './../' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($path)) {
        require_once $path;
        return true;
    }

    return false;
};

spl_autoload_register($autoload);

$app = new Core\App();
$app->addRoutes('/registration', 'GET', UserController::class, 'getRegistrate');
$app->addRoutes('/registration', 'POST', UserController::class, 'registrate');
$app->addRoutes('/login', 'GET', UserController::class, 'getLogin');
$app->addRoutes('/login', 'POST', UserController::class, 'login');
$app->addRoutes('/logout', 'GET', UserController::class, 'logout');
$app->addRoutes('/catalog', 'GET', ProductController::class, 'catalog');
$app->addRoutes('/profile', 'GET', UserController::class, 'profile');
$app->addRoutes('/editProfile', 'GET', UserController::class, 'getEditForm');
$app->addRoutes('/editProfile', 'POST', UserController::class, 'editProfile');
$app->addRoutes('/add-product', 'GET', ProductController::class, 'getAddProductForm');
$app->addRoutes('/add-product', 'POST', ProductController::class, 'addProduct');
$app->addRoutes('/cart', 'GET', UserProductController::class, 'cart');
$app->addRoutes('/create-order', 'GET', OrderController::class, 'getCheckoutForm');
$app->addRoutes('/create-order', 'POST', OrderController::class, 'handleCheckout');
$app->addRoutes('/my-orders', 'GET', OrderController::class, 'getUserOrders');
$app->run();