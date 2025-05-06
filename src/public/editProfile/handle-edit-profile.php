<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Проверяем, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

function validateData(array $data):array
{
    $errors = [];

    if (isset($data['username']) && $data['username'] != '') {
        $errorName = validateName($data);
        if (!empty($errorName)) {
            $errors['username'] = $errorName;
        }
    }
    if (isset($data['email']) && $data['email'] != '') {
        $errorEmail = validateEmail($data);
        if (!empty($errorEmail)) {
            $errors['email'] = $errorEmail;
        }
    }

    if (isset($data['new_password']) && $data['new_password'] != '') {
        $errorPassword = validateOldPassword($data);
        if (!empty($errorPassword)) {
            $errors['old_password'] = $errorPassword;
        }
    }

    if (isset($data['new_password']) && $data['new_password'] != '') {
        $errorNewPassword = validateNewPassword($data);
        if (!empty($errorNewPassword)) {
            $errors['new_password'] = $errorNewPassword;
        }
    }

    return $errors;
}

function validateName(array $data): null|string
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

function validateEmail(array $data): null|string
{
    if (isset($data['email'])) {
        $email = $data['email'];
        if (strlen($email) <= 4) {
            return 'Слишком короткий почтовый адрес';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return 'Неправильный формат email';
        } else {
        // Проверка, что email не занят другим пользователем
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $userId = $_SESSION['user_id'];
        if ($user['id'] !== $userId ) {
            return 'Данный email уже зарегистрирован другим пользователем';
        } else {
            return null;
        }
        }
    } else {
        return 'Введите email';
    }
}

function validateOldPassword($data): null|string
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
function validateNewPassword(array $data): null|string
{
    if (!empty($data['new_password'])) {

        $password = $data['new_password'];

        if (strlen($password) <= 5) {
            return 'Недопустимая длина нового пароля (минимум 6 символов)';
        }

        if (isset($data['repeat_password'])) {

            $repeatPassword = $data['repeat_password'];

            if ($repeatPassword !== $password) {
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

$errors = validateData($_POST);

    // Если ошибок нет - обновляем данные
    if (empty($errors)) {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];
        $userId = $_SESSION['user_id'];


        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        if (isset($name) && ($name !== '')) {
            $stmt = $pdo->prepare("UPDATE users SET name = :name where id = :id");
            $stmt->execute(['name' => $name, 'id' => $userId]);
        }

        if (isset($email) && ($email !== '')) {
            $stmt = $pdo->prepare("UPDATE users SET email = :email where id = :id");
            $stmt->execute(['email' => $email, 'id' => $userId]);
        }

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password where id = :id");
            $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
        }

        header('Location: /profile');
        exit;

    } else {
        print_r($errors);
    }
