<?php

print_r($_GET);

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

$pdo->exec("INSERT INTO users (name, email, password) VALUES ('Ivan','ttt@mail.ru','asaa')");
