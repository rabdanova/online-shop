<?php
session_start();
// Проверяем, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: /login_form.php");
    exit;
}

function ValidateData(array $data):array
{
    $errors = [];

    if (isset($data['username']) && $data['username'] != '') {
        $errorName = ValidateName($data);
        if (!empty($errorName)) {
            $errors['username'] = $errorName;
        }
    }
    if (isset($data['email']) && $data['email'] != '') {
        $errorEmail = ValidateEmail($data);
        if (!empty($errorEmail)) {
            $errors['email'] = $errorEmail;
        }
    }

    if (isset($data['new_password']) && $data['new_password'] != '') {
        $errorPassword = ValidateOldPassword($data);
        if (!empty($errorPassword)) {
            $errors['old_password'] = $errorPassword;
        }
    }

    if (isset($data['new_password']) && $data['new_password'] != '') {
        $errorNewPassword = ValidateNewPassword($data);
        if (!empty($errorNewPassword)) {
            $errors['new_password'] = $errorNewPassword;
        }
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

        if (isset($name) && ($name !== '')) {
            $stmt = $pdo->prepare("UPDATE users SET name = :name where id = :id");
            $stmt->execute(['name' => $name, 'id' => $userId]);
            echo 'Имя пользователя успешно изменено'."\n";
        }

        if (isset($email) && ($email !== '')) {
            $stmt = $pdo->prepare("UPDATE users SET email = :email where id = :id");
            $stmt->execute(['email' => $email, 'id' => $userId]);
            echo 'Email успешно изменен'."\n";
        }

        if (!empty($new_password)) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password where id = :id");
            $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
            echo 'Пароль успешно изменен'."\n";
        }

    } else {
        print_r($errors);
    }
