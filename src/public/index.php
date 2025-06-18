<?php

use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Controllers\UserProductController;
use Core\App;

require_once "./../Core/Autoload/Autoloader.php";

$path = dirname(__DIR__);
\Core\Autoload\Autoloader::register($path);


$app = new Core\App();
$app->get('/registration',UserController::class, 'getRegistrate');
$app->post('/registration',UserController::class, 'registrate');
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login',UserController::class, 'login');
$app->get('/logout', UserController::class, 'logout');
$app->get('/catalog',ProductController::class, 'catalog');
$app->get('/profile', UserController::class, 'profile');
$app->get('/editProfile', UserController::class, 'getEditForm');
$app->post('/editProfile',UserController::class, 'editProfile');
$app->post('/decrease-product',ProductController::class, 'decreaseProduct');
$app->post('/add-product',ProductController::class, 'addProduct');
$app->get('/cart',UserProductController::class, 'cart');
$app->get('/create-order',OrderController::class, 'getCheckoutForm');
$app->post('/create-order',OrderController::class, 'handleCheckout');
$app->get('/my-orders',OrderController::class, 'getUserOrders');
$app->run();