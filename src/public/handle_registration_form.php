<?php

$name = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat-pas'];


function IsValidData():array
{
    $errors = [];

    if (isset($_POST['username']))
    {
        $name = $_POST['username'];
        if ((strlen($name) <= 3)) {
            $errors['username'] = 'Недопустимая длина имени';
        }
    } else {
        $errors['username'] = 'Введите имя пользователя';
    }


    if (isset($_POST['email']))
    {
        $email = $_POST['email'];

        if (strlen($email) <= 4) {
            $errors['email'] = 'Слишком короткий почтовый адрес';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Добавьте си мвол @';
        }
    } else {
        $errors['email'] = 'Введите почту пользователя';
    }


    if (isset($_POST['password']))
    {
        $password = $_POST['password'];

        if (strlen($password) <= 5) {
            $errors['password'] = 'Недопустимая длина пароля';
        }
    } else {
        $errors['password'] = 'Введите пароль пользователя';
    }


    if (isset($_POST['repeat-pas']))
    {
        $repeat_password = $_POST['repeat-pas'];

        if ($repeat_password !== $password) {
            $errors['repeat-pas'] = 'Пароли не совпадают';
        }
    } else {
        $errors['repeat-pas'] = 'Введите пароль пользователя';
    }

    return $errors;
}


$errors = IsValidData();

    if (empty($errors)){

        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $password = password_hash($password, PASSWORD_DEFAULT);

        $result = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $result->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        $res2 = $pdo->prepare("select * from users where email = :email");
        $res2->execute(['email' => $email]);

        $data = $res2->fetch();
        print_r($data);
    }

require_once './registration_form.php';
    ?>



