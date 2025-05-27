<?php

namespace Model;
class Product extends Model
{
    public function getAllProducts():array
    {
        $stmt = $this->getPDO()->query('select * from products');
        return $stmt->fetchAll();
    }

    public function getByTwoId($userId, $productId):array|false
    {

        $res = $this->getPDO()->prepare("select amount from user_products where user_id=:user_id and product_id=:product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $res->fetch();
        return $result;
    }

    public function insertById($userId, $productId, $amount)
    {
        $res = $this->getPDO()->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }

    public function updateById($userId, $productId,$newAmount)
    {
        $res = $this->getPDO()->prepare("Update user_products set amount = :amount where user_id = :user_id and product_id = :product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $newAmount]);
    }

    public function getByProductId($productId):array|false
    {
        $stmt = $this->getPDO()->prepare("SELECT id from products where id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();

        return $result;
    }

    public function getById(int $productId):array|false
    {
        $stmt = $this->getPDO()->prepare("SELECT * from products where id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();

        return $result;
    }
}