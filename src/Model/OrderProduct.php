<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private string $orderId;
    private string $productId;
    private string $amount;
    private string $price;
    private string $name;
    private string $imageUrl;
    private string $totalSum;
    public function create(int $orderId, int $productId, int $amount)
    {
        $stmt = $this->getPDO()->prepare("INSERT INTO order_products (order_id, product_id, amount) VALUES (:orderId, :productId, :amount)");

        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function getAllByOrderId($orderId):array|null
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $arr = [];
        foreach ($orderProducts as $item) {
            if (!$item) {
                return null;
            }
            $obj = new self();
            $obj->id = $item["id"];
            $obj->orderId = $item["order_id"];
            $obj->productId = $item["product_id"];
            $obj->amount = $item["amount"];

            $arr[] = $obj;
        }
        return $arr;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function setTotalSum(string $totalSum): void
    {
        $this->totalSum = $totalSum;
    }

    public function getTotalSum(): string
    {
        return $this->totalSum;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }


}