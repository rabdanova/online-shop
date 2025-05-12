<?php

class User
{   public function getRegistrate()
    {
        require_once './pages/registration-form.php';
    }

    public function getLogin()
    {
        require_once './pages/login-form.php';
    }

    public function getEditForm()
    {
        require_once './pages/edit-profile-form.php';
    }
    public function registrate()
    {
        $errors = $this->isValidData($_POST);

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
    }

    public function login()
    {
        $errors = $this->validateLogin($_POST);

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
        $this->getLogin();
    }

    public function profile(){
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

            $userId = $_SESSION['user_id'];
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->query("SELECT * FROM users WHERE id = " . $userId);
            $user = $stmt->fetch();
            require_once './pages/profile-page.php';
    }

    public function editProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $errors = $this->validateEditProfile($_POST);

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
    }
    private function validateEditProfile(array $data):array
    {
        $errors = [];

        if (isset($data['username']) && $data['username'] != '') {
            $errorName = $this->validateNameEdit($data);
            if (!empty($errorName)) {
                $errors['username'] = $errorName;
            }
        }
        if (isset($data['email']) && $data['email'] != '') {
            $errorEmail = $this->validateEmailEdit($data);
            if (!empty($errorEmail)) {
                $errors['email'] = $errorEmail;
            }
        }

        if (isset($data['new_password']) && $data['new_password'] != '') {
            $errorPassword = $this->validateOldPasswordEdit($data);
            if (!empty($errorPassword)) {
                $errors['old_password'] = $errorPassword;
            }
        }

        if (isset($data['new_password']) && $data['new_password'] != '') {
            $errorNewPassword = $this->validateNewPasswordEdit($data);
            if (!empty($errorNewPassword)) {
                $errors['new_password'] = $errorNewPassword;
            }
        }

        return $errors;
    }

     private function validateNameEdit(array $data): null|string
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

    private function validateEmailEdit(array $data): null|string
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

    private function validateOldPasswordEdit($data): null|string
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
    private function validateNewPasswordEdit(array $data): null|string
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
    private function validateLogin(array $data): array
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
private function IsValidData(array $data):array
{
    $errors = [];

    $errorName = $this->validateName($data);
    if (!empty($errorName)) {
        $errors['name'] = $errorName;
    }

    $errorEmail = $this->validateEmail($data);
    if (!empty($errorEmail)) {
        $errors['email'] = $errorEmail;
    }

    $errorPassword = $this->validatePassword($data);
    if (!empty($errorPassword)) {
        $errors['password'] = $errorPassword;
    }

    return $errors;
}

 private function validateName(array $data):null|string
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

private function validateEmail(array $data):null|string
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
private function validatePassword(array $data):string|null
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
}