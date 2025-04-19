<?php

$name = $_GET['username'];
$email = $_GET['email'];
$password = $_GET['password'];
$repeat_password = $_GET['repeat-pas'];

function IsValidData($name,$email,$password,$repeat_password)
{
    $flag = true;

    if ((strlen($name) <= 3)) {
        $flag = false;
        echo 'Недопустимая длина имени' . "\n";
    }

    if (strlen($email) <= 4) {
        $flag = false;
        echo 'Слишком короткий почтовый адрес' . "\n";
    }

    if (strlen($password) <= 5) {
        $flag = false;
        echo 'Недопустимая длина пароля. Пожалуйста, придумайте более длинный пароль!' . "\n";
    }

    if ($repeat_password !== $password) {
        $flag = false;
        echo 'Пароли не совпадают' . "\n";
    }

    if ($flag === true) {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')");

        $result = $pdo->query("select * from users order by id desc limit 1");

        $data = $result->fetch();

        print_r($data);
    }
}

IsValidData($name,$email,$password,$repeat_password);