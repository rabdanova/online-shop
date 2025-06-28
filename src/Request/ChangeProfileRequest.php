<?php

namespace Request;

use Model\User;
use Service\AuthService;

class ChangeProfileRequest
{
    private User $userModel;
    private AuthService $authService;
    public function __construct(private array $data)
    {
        $this->userModel = new User();
        $this->authService = new AuthService();
    }
    public function getName() : string
    {
        return $this->data['username'];
    }

    public function getEmail() : string
    {
        return $this->data['email'];
    }

    public function getNewPassword() : string
    {
        return $this->data['new_password'];
    }

    public function getOldPassword() : string
    {
        return $this->data['old_password'];
    }

    public function validateEditProfile(): array
    {
        $errors = [];
        if (isset($this->data['username']) && $this->data['username'] != '') {
            $errorName = $this->validateNameEdit();
            if (!empty($errorName)) {
                $errors['username'] = $errorName;
            }
        }
        if (isset($this->data['email']) && $this->data['email'] != '') {
            $errorEmail = $this->validateEmailEdit();
            if (!empty($errorEmail)) {
                $errors['email'] = $errorEmail;
            }
        }
        if (isset($this->data['new_password']) && $this->data['new_password'] != '') {
            $errorPassword = $this->validateOldPasswordEdit();
            if (!empty($errorPassword)) {
                $errors['old_password'] = $errorPassword;
            }
        }
        if (isset($this->data['new_password']) && $this->data['new_password'] != '') {
            $errorNewPassword = $this->validateNewPasswordEdit();
            if (!empty($errorNewPassword)) {
                $errors['new_password'] = $errorNewPassword;
            }
        }
        return $errors;
    }
    private function validateNameEdit(): null|string
    {
        if (isset($this->data['username'])) {
            $name = $this->data['username'];
            if (strlen($name) <= 3) {
                return 'Недопустимая длина имени';
            } else {
                return null;
            }
        } else {
            return 'Введите имя пользователя';
        }
    }

    private function validateEmailEdit(): null|string
    {
        if (isset($this->data['email'])) {
            $email = $this->data['email'];
            if (strlen($email) <= 4) {
                return 'Слишком короткий почтовый адрес';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return 'Неправильный формат email';
            } else {
                // Проверка, что email не занят другим пользователем

                $userEmail = $this->userModel->getByEmail($email);

                $user = $this->authService->getCurrentUser();
                if ($userEmail !== null && $userEmail->getId() !== $user->getId()) {
                    return 'Данный email уже зарегистрирован другим пользователем';
                } else {
                    return null;
                }
            }
        } else {
            return 'Введите email';
        }
    }

    private function validateOldPasswordEdit(): null|string
    {
        if (isset($this->data['old_password'])) {
            $old_password = $this->data['old_password'];
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

    private function validateNewPasswordEdit(): null|string
    {
        if (!empty($this->data['new_password'])) {

            $password = $this->data['new_password'];

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
}