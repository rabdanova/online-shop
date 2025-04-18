<?php

$pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

//$pdo->exec("INSERT INTO users (name, email, password) VALUES ('Ivan','ttt@mail.ru','asaa')");

$statement = $pdo->query("select * from users");
$data = $statement->fetchAll();
echo "<pre>";
print_r($data);
echo "</pre>";