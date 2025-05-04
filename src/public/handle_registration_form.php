<?php
function IsValidData(array $data):array
{
    $errors = [];

    $errorName = ValidateName($data);
    if (!empty($errorName)) {
        $errors['name'] = $errorName;
    }

    $errorEmail = ValidateEmail($data);
    if (!empty($errorEmail)) {
        $errors['email'] = $errorEmail;
    }

    $errorPassword = ValidatePassword($data);
    if (!empty($errorPassword)) {
        $errors['password'] = $errorPassword;
    }

    return $errors;
}

function ValidateName(array $data):null|string
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

function ValidateEmail(array $data):null|string
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
function ValidatePassword(array $data):string|null
{
    $message = null;

    if (isset($data['password'])) {
        $password = $data['password'];

        if (strlen($password) <= 5) {
            $message = 'Недопустимая длина пароля';
        }
        if (isset($data['repeat-pas'])) {
            $repeat_password = $data['repeat-pas'];

            if ($repeat_password !== $password) {
                $message = 'Пароли не совпадают';
            }
        }


    } else {
        $message = 'Недопустимая длина пароля';
    }

    return $message;
}


$errors = IsValidData($_POST);

    if (empty($errors)){
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repeat_password = $_POST['repeat-pas'];

        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $password = password_hash($password, PASSWORD_DEFAULT);

        $res = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $res->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        header("location: /login");
    }

require_once './registration_form.php';
    ?>



