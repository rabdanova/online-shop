<?php

namespace Model;

class UserProduct extends Model
{
    public function getByUserId($userId):array
    {
        $stmt = $this->getPDO()->prepare('select * from user_products where user_id=:user_id');
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll();

        return $data;
    }

    public function getByProductId($productId):array|false
    {
        $stmt = $this->getPDO()->prepare('select * from products where id=:id');
        $stmt->execute(['id' => $productId]);
        return $stmt->fetch();

    }

    public function deleteByUserId(int $userId)
    {
        $stmt = $this->getPDO()->prepare('delete from user_products where user_id=:user_id');
        $stmt->execute(['user_id' => $userId]);
    }
}