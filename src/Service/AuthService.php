<?php

namespace Service;

use Model\User;

class AuthService
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function check():bool
    {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser():?User
    {
        $this->startSession();

        if ($this->check()){
            $userId = $_SESSION['user_id'];
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
                $this->startSession();
                $_SESSION['user_id'] = $user->getId();

                return true;
            } else {
                return false;
            }
        }
    }

    public function logout()
    {
        $this->startSession();
        $_SESSION = [];
        session_destroy();
    }
    private function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}