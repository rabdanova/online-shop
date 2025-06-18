<?php
namespace Controllers;
use Model\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    public function getRegistrate()
    {
        require_once '../Views/registration.php';
    }

    public function getLogin()
    {
        require_once '../Views/login.php';
    }

    public function getEditForm()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
        }

        $user = $this->authService->getCurrentUser();

        $this->userModel->getById($user->getId());
        require_once '../Views/edit-profile.php';
    }

    public function registrate()
    {
        $errors = $this->isValidData($_POST);

        if (empty($errors)) {
            $name = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $password = password_hash($password, PASSWORD_DEFAULT);

            $this->userModel->insertData($name,$email,$password);


            header("location: /login");
        } else {
            print_r($errors);
        }
    }

    public function login()
    {
        $errors = $this->validateLogin($_POST);

        if (empty($errors)) {
            $result = $this->authService->auth($_POST['email'],$_POST['password']);

            if ($result === true) {
                header('Location: /catalog');
                exit;
            } else {
                return "Username or password is incorrect";
            }
        }
        $this->getLogin();
    }

    public function profile()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $user = $this->authService->getCurrentUser();

        require_once '../Views/profile.php';
    }

    public function editProfile()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $errors = $this->validateEditProfile($_POST);

        if (empty($errors)) {
            $name = $_POST['username'];
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];
            $user = $this->authService->getCurrentUser();


            if (isset($name) && ($name !== '')) {
                $this->userModel->updateNameById($name, $user->getId());
            }

            if (isset($email) && ($email !== '')) {
                $this->userModel->updateEmailById($email, $user->getId());
            }

            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->userModel->updatePasswordById($hashedPassword, $user->getId());
            }

            header('Location: /profile');
            exit;

        } else {
            print_r($errors);
        }
    }

    public function logout()
    {
        $this->authService->logout();
        header("Location: /login");
        exit;
    }

    private function validateEditProfile(array $data): array
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

                $userEmail = $this->userModel->getByEmail($email);

                $user = $this->authService->getCurrentUser();
                if ($userEmail !== null && $user['id'] !== $user) {
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
            $user = $this->authService->getCurrentUser();

            $userData = $this->userModel->getById($user->getId());

            $passwordDB = $userData[0]['password'];

            if (password_verify($old_password, $passwordDB)) {
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

    private function IsValidData(array $data): array
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

    private function validateName(array $data): null|string
    {
        if (isset($data['username'])) {
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

    private function validateEmail(array $data): null|string
    {
        $message = null;

        if (isset($data['email'])) {

            $email = $data['email'];

            if (strlen($email) <= 4) {
                $message = 'Слишком короткий почтовый адрес';

            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $message = 'Неправильный формат email';
            } else {

                $user = $this->userModel->getByEmail($email);
                if ($user !== false) {
                    $message = "Данный email уже зарегистрирован";
                }
            }
        } else {
            $message = 'Введите email';
        }

        return $message;
    }

    private function validatePassword(array $data): string|null
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