<?php
session_start();
// Проверяем, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: /login_form.php");
    exit;
} else {
    $userId = $_SESSION['user_id'];
    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
    $stmt = $pdo->prepare("SELECT name, email, password FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    if (empty($user)) {
        die('Пользователь не найден');
    }
}

function ValidateData(array $data):array
{
    $errors = [];

    $errorName = ValidateName($data);
    if (!empty($errorName)) {
        $errors['username'] = $errorName;
    }

    $errorEmail = ValidateEmail($data);
    if (!empty($errorEmail)) {
        $errors['email'] = $errorEmail;
    }


    $errorPassword = ValidateOldPassword($data);
    if (!empty($errorPassword)){
        $errors['old_password'] = $errorPassword;
    }

    $errorNewPassword = ValidateNewPassword($data);
    if (!empty($errorNewPassword)) {
        $errors['new_password'] = $errorNewPassword;
    }

    return $errors;
}

function ValidateName(array $data): null|string
{
    if (isset($data['username'])) {
        $name = $data['username'];
        if (strlen($name) <= 3) {
            return 'Недопустимая длина имени';
        } else {
            return null;
        }
    } else {
        return 'Введите имя пользователя';
    }
}

function ValidateEmail(array $data): null|string
{
    if (isset($data['email'])) {
        $email = $data['email'];
        if (strlen($email) <= 4) {
            return 'Слишком короткий почтовый адрес';
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return 'Неправильный формат email';
        }
        // Проверка, что email не занят другим пользователем
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return 'Данный email уже зарегистрирован другим пользователем';
        } else {
            return null;
        }
    } else {
        return 'Введите email';
    }
}

function ValidateOldPassword($data): null|string
{
    if (isset($data['old_password'])) {
        $old_password = $data['old_password'];
        $userId = $_SESSION['user_id'];
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        $userData = $stmt->fetchAll();

        $passwordDB = $userData[0]['password'];

      if (password_verify($old_password, $passwordDB)){
          return null;
      } else {
          return 'Введенный пароль не совпадает со старым';
      }

    } else {
        return 'Введите старый пароль';
    }
}
function ValidateNewPassword(array $data): null|string
{
    if (!empty($data['new_password'])) {

        $password = $data['new_password'];

        if (strlen($password) <= 5) {
            return 'Недопустимая длина нового пароля (минимум 6 символов)';
        }

        if (isset($data['repeat_password'])) {

            $repeat_password = $data['repeat_password'];

            if ($repeat_password !== $password) {
                return 'Пароли не совпадают';
            } else {
                return null;
            }
        } else {
            return 'Подтвердите новый пароль';
        }

    } else {
        return 'Введите новый пароль';
    }
}

$errors = ValidateData($_POST);

    // Если ошибок нет - обновляем данные
    if (empty($errors)) {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];
        $userId = $_SESSION['user_id'];


        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        $result = $pdo->prepare("UPDATE users SET name = :name, email =:email, password = :password where id = :id");
        $result->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword, 'id' => $userId]);

        $stmt = $pdo->prepare("select * from users where email = :email");
        $stmt->execute([':email' => $email]);

        $result = $stmt->fetch();
        print_r($result);

    } else {
        print_r($errors);
    }
