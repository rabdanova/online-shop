<?php

namespace Model;
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imageUrl;
    private int $userId;
    private int $productId;
    private int $amount;
    public function getAllProducts():array|null
    {
        $stmt = $this->getPDO()->query('select * from products');
        $products = $stmt->fetchAll();
        $arr = [];
        foreach ($products as $product) {
            if (!$product) {
                return null;
            }
            $obj = new self();
            $obj->id = $product["id"];
            $obj->name = $product["name"];
            $obj->description = $product["description"];
            $obj->price = $product["price"];
            $obj->imageUrl = $product["image_url"];

            $arr[] = $obj;
        }
        return $arr;
    }

    public function getByProductId($productId):self|null
    {
        $stmt = $this->getPDO()->prepare('select * from products where id=:id');
        $stmt->execute(['id' => $productId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }
        $obj = new self();
        $obj->id = $result["id"];
        $obj->name = $result["name"];
        $obj->description = $result["description"];
        $obj->price = $result["price"];
        $obj->imageUrl = $result["image_url"];

        return $obj;
    }

    public function getById(int $productId):self|null
    {
        $stmt = $this->getPDO()->prepare("SELECT * from products where id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $result["id"];
        $obj->name = $result["name"];
        $obj->description= $result["description"];
        $obj->price = $result["price"];
        $obj->imageUrl = $result["image_url"];

        return $obj;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }


}