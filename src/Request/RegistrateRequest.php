<?php

namespace Request;
use Model\User;
class RegistrateRequest
{
    private User $userModel;
    public function __construct(private array $data)
    {
        $this->userModel = new User();
    }
    public function getName() : string
    {
        return $this->data['username'];
    }

    public function getEmail() : string
    {
        return $this->data['email'];
    }

    public function getPassword() : string
    {
        return $this->data['password'];
    }

    public function getRepeatPassword() : string
    {
        return $this->data['repeat_pas'];
    }

    public function IsValidData(): array
    {
        $errors = [];

        $errorName = $this->validateName();
        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }

        $errorEmail = $this->validateEmail();
        if (!empty($errorEmail)) {
            $errors['email'] = $errorEmail;
        }

        $errorPassword = $this->validatePassword();
        if (!empty($errorPassword)) {
            $errors['password'] = $errorPassword;
        }

        return $errors;
    }

    private function validateName(): null|string
    {
        if (isset($this->data['username'])) {
            $name = $this->data['username'];
            if ((strlen($name) <= 3)) {
                return 'Недопустимая длина имени';
            } else {
                return NULL;
            }

        } else {
            return 'Введите имя пользователя';
        }
    }
    private function validateEmail(): null|string
    {
        $message = null;

        if (isset($this->data['email'])) {

            $email = $this->data['email'];

            if (strlen($email) <= 4) {
                $message = 'Слишком короткий почтовый адрес';

            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $message = 'Неправильный формат email';
            } else {

                $user = $this->userModel->getByEmail($email);
                if ($user !== null) {
                    $message = "Данный email уже зарегистрирован";
                }
            }
        } else {
            $message = 'Введите email';
        }

        return $message;
    }

    private function validatePassword(): string|null
    {
        $message = null;

        if (isset($this->data['password'])) {
            $password = $this->data['password'];

            if (strlen($password) <= 5) {
                $message = 'Недопустимая длина пароля';
            }
            if (isset($this->data['repeat-pas'])) {
                $repeatPassword = $this->data['repeat-pas'];

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