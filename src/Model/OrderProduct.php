<?php

namespace Model;

class OrderProduct extends Model
{
    public function create(int $orderId, int $productId, int $amount)
    {
        $stmt = $this->getPDO()->prepare("INSERT INTO order_products (order_id, product_id, amount) VALUES (:orderId, :productId, :amount)");

        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function getOrderProducts($orderId){
        $stmt = $this->getPDO()->prepare("SELECT * FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        return $orderProducts;
    }

}