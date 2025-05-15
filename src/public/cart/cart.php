<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
}
$userId = $_SESSION['user_id'];

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare('select * from user_products where user_id=:user_id');
$stmt->execute(['user_id' => $userId]);
$data = $stmt->fetchAll();


$cart = [];

foreach ($data as $product) {

    $productId = $product['product_id'];

    $stmt = $pdo->prepare('select * from products where id=:id');
    $stmt->execute(['id' => $productId]);
    $result = $stmt->fetch();
    $cart[] = $result;
}
//echo "<pre>";
//print_r($cart);
//exit;
require_once './cart/cart.php';