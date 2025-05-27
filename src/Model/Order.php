<?php

namespace Model;

class Order extends Model
{
    public function create(string $name, string $phone, string $address, string $comment, int $userId)
    {
        $stmt = $this->getPDO()->prepare("INSERT INTO orders (
                    name, phone_number, address, comment, user_id) VALUES (:name, :phone_number, :address, :comment, :user_id) RETURNING id");
        $stmt->execute(['name' => $name, 'phone_number' => $phone, 'address' => $address, 'comment' => $comment, 'user_id' => $userId]);

        $data = $stmt->fetch();

        return $data['id'];
    }

    public function getByUserId($userId)
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $userOrders = $stmt->fetchAll();

        return $userOrders;
    }
}