<?php
class Cart
{
    public function getByUserId($userId):array
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare('select * from user_products where user_id=:user_id');
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll();

        return $data;
    }

    public function getByProductId($productId):array|false
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare('select * from products where id=:id');
        $stmt->execute(['id' => $productId]);
        return $stmt->fetch();

    }
}