<?php

$username = $_POST["username"];
$password = $_POST["password"];

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $username]);
$user = $stmt->fetch();

$errors = [];

    if ($user === false) {
        $errors['username'] = "Username or password is incorrect";
    } else {
        $passwordDb = $user["password"];

        if (password_verify($password, $passwordDb)) {
            session_start();
            $_SESSION['user_id'] = $user['id'];

            header('Location: /catalog.php');
        } else {
            $errors['username'] = "Username or password is incorrect";
        }
    }

require_once './login_form.php';