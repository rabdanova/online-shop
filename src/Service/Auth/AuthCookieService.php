<?php

namespace Service\Auth;

use Model\User;

class AuthCookieService implements AuthInterface
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function check():bool
    {
        return isset($_COOKIE['user_id']);
    }

    public function getCurrentUser():?User
    {
        if ($this->check()){
            $userId = $_COOKIE['user_id'];
            return $this->userModel->getById($userId);
        } else {
            return null;
        }

    }

    public function auth(string $email, string $password):bool
    {
        $user = $this->userModel->getByEmail($email);
        if (!$user) {
            return false;
        } else {
            $passwordDb = $user->getPassword();
            if (password_verify($password, $passwordDb)) {
                setcookie('user_id', $user->getId());
                return true;
            } else {
                return false;
            }
        }
    }

    public function logout()
    {
       setcookie('user_id', "", time() - 3600, '/');
       unset($_COOKIE['user_id']);
    }
}