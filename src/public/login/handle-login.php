<?php
function validate(array $data): array
{
    $errors = [];

    if (!isset($data['username'])) {
        $errors['username'] = 'Username is required';
    }
    if (!isset($data['password'])) {
        $errors['password'] = 'Password is required';
    }
    return $errors;
}

$errors = validate($_POST);

if (empty($errors)) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $username]);
    $user = $stmt->fetch();

    if ($user === false) {
        $errors['username'] = "Username or password is incorrect";
    } else {
        $passwordDb = $user["password"];

        if (password_verify($password, $passwordDb)) {
            session_start();
            $_SESSION['user_id'] = $user['id'];

            header('Location: /catalog');

        } else {
            $errors['username'] = "Username or password is incorrect";
        }
    }
}


require_once './login/login-form.php';