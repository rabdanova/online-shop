<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
}
$user_id = $_SESSION['user_id'];

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare('select * from user_products where user_id=:user_id');
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetchAll();


$cart = [];

foreach ($result as $product) {

    $product_id = $product['product_id'];

    $stmt = $pdo->prepare('select * from products where id=:id');
    $stmt->execute(['id' => $product_id]);
    $result = $stmt->fetchAll();
    $cart[] = $result;
}
//echo "<pre>";
//print_r($products);
require_once './cart_page.php';