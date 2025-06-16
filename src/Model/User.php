<?php

namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function insertData($name,$email,$password)
    {
        $res = $this->getPDO()->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $res->execute(['name' => $name, 'email' => $email, 'password' => $password]);
    }
    public function getByEmail($email): self|null
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $result["id"];
        $obj->name = $result["name"];
        $obj->email = $result["email"];
        $obj->password = $result["password"];

        return $obj;
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

    public function getById($userId): self|null
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $result["id"];
        $obj->name = $result["name"];
        $obj->email = $result["email"];
        $obj->password = $result["password"];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }



}