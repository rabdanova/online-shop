<?php

namespace Request;

class LoginRequest
{
    public function __construct(private array $data)
    {
    }

    public function getEmail() : string
    {
        return $this->data['email'];
    }

    public function getPassword() : string
    {
        return $this->data['password'];
    }

    public function validateLogin(): array
    {
        $errors = [];

        if (empty($this->data['email'])) {
            $errors['email'] = 'Email is required';
        }
        if (empty($this->data['password'])) {
            $errors['password'] = 'Password is required';
        }
        return $errors;
    }



}