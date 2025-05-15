<?php
function IsValidData(array $data):array
{
    $errors = [];

    $errorName = validateName($data);
    if (!empty($errorName)) {
        $errors['name'] = $errorName;
    }

    $errorEmail = validateEmail($data);
    if (!empty($errorEmail)) {
        $errors['email'] = $errorEmail;
    }

    $errorPassword = validatePassword($data);
    if (!empty($errorPassword)) {
        $errors['password'] = $errorPassword;
    }

    return $errors;
}

function validateName(array $data):null|string
{
    if (isset($data['username']))
    {
        $name = $data['username'];
        if ((strlen($name) <= 3)) {
            return 'Недопустимая длина имени';
        } else {
            return NULL;
        }

    } else {
        return 'Введите имя пользователя';
    }
}

function validateEmail(array $data):null|string
{
    $message = null;

    if (isset($data['email'])) {

        $email = $data['email'];

        if (strlen($email) <= 4) {
            $message =  'Слишком короткий почтовый адрес';

        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $message = 'Неправильный формат email';
        } else {
            $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare("select count(*) from users where email = :email");
            $stmt->execute(['email' => $email]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $message = "Данный email уже зарегистрирован";
            }
        }
    } else {
        $message = 'Введите email';
    }

    return $message;
}
function validatePassword(array $data):string|null
{
    $message = null;

    if (isset($data['password'])) {
        $password = $data['password'];

        if (strlen($password) <= 5) {
            $message = 'Недопустимая длина пароля';
        }
        if (isset($data['repeat-pas'])) {
            $repeatPassword = $data['repeat-pas'];

            if ($repeatPassword !== $password) {
                $message = 'Пароли не совпадают';
            }
        }


    } else {
        $message = 'Недопустимая длина пароля';
    }

    return $message;
}


$errors = isValidData($_POST);

    if (empty($errors)){
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repeatPassword = $_POST['repeat-pas'];

        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $password = password_hash($password, PASSWORD_DEFAULT);

        $res = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $res->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        header("location: /login");
    }

require_once './registration/registration.php';
    ?>



