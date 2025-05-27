<?php

namespace Model;

class User extends Model
{
    public function insertData($name,$email,$password)
    {
        $res = $this->getPDO()->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $res->execute(['name' => $name, 'email' => $email, 'password' => $password]);
    }
    public function getByEmail($email): array|false
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();

        return $result;
    }

    public function updateEmailById($email, $userId)
    {
        $stmt = $this->getPDO()->prepare("UPDATE users SET email = :email where id = :id");
        $stmt->execute(['email' => $email, 'id' => $userId]);
    }

    public function updateNameById($name, $userId)
    {
        $stmt = $this->getPDO()->prepare("UPDATE users SET name = :name where id = :id");
        $stmt->execute(['name' => $name, 'id' => $userId]);
    }

    public function updatePasswordById($hashedPassword, $userId)
    {
        $stmt = $this->getPDO()->prepare("UPDATE users SET password = :password where id = :id");
        $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
    }

    public function getById($userId):array|false
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch();

        return $result;
    }

}