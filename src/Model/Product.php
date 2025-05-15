<?php
class Product
{
    public function getAllProducts():array
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $stmt = $pdo->query('select * from products');
        return $stmt->fetchAll();
    }

    public function getByTwoId($userId, $productId):array|false
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');

        $res = $pdo->prepare("select amount from user_products where user_id=:user_id and product_id=:product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $res->fetch();
        return $result;
    }

    public function insertById($userId, $productId, $amount)
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
        $res = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }

    public function updateById($userId, $productId,$newAmount)
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
        $res = $pdo->prepare("Update user_products set amount = :amount where user_id = :user_id and product_id = :product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $newAmount]);
    }

    public function getByProductId($productId):array|false
    {
        $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("SELECT id from products where id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();

        return $result;
    }
}