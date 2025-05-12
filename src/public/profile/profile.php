<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = " . $userId);
    $user = $stmt->fetch();
    require_once './profile/profile-page.php';
} else {
    header("Location: /login");
}