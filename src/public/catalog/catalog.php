<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
}

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

$stmt = $pdo->query('select * from products');
$products = $stmt->fetchAll();

require_once './catalog/catalog.php';

