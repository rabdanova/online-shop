<?php

namespace Model;

class UserProduct extends Model
{
    private int $id;
    private string $userId;
    private string $productId;
    private int $amount;
    private string $name;
    private int $price;
    private string $imageUrl;
    private string $description;

    public function getByUserId($userId):array|null
    {
        $stmt = $this->getPDO()->prepare("select * from {$this->getTableName()} where user_id=:user_id");
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll();
        $arr = [];
        foreach ($data as $item) {
            if (!$item) {
                return null;
            }
            $obj = new self();
            $obj->id = $item["id"];
            $obj->userId = $item["user_id"];
            $obj->productId = $item["product_id"];
            $obj->amount = $item["amount"];

            $arr[] = $obj;
        }
        return $arr;
    }
    public function getOneByProductAndUserId($userId, $productId):self|null
    {
        $res = $this->getPDO()->prepare("select amount from {$this->getTableName()} where user_id=:user_id and product_id=:product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $res->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();
        $obj->amount = $result["amount"];

        return $obj;
    }

    public function insertById($userId, $productId, $amount)
    {
        $res = $this->getPDO()->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }

    public function updateById($userId, $productId,$newAmount)
    {
        $res = $this->getPDO()->prepare("Update {$this->getTableName()} set amount = :amount where user_id = :user_id and product_id = :product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $newAmount]);
    }


    public function deleteByUserId(int $userId)
    {
        $stmt = $this->getPDO()->prepare("delete from {$this->getTableName()} where user_id=:user_id");
        $stmt->execute(['user_id' => $userId]);
    }

    public function deleteOneByProductAndUserId(int $productId, $userId)
    {
        $stmt = $this->getPDO()->prepare("delete from {$this->getTableName()} where user_id = :user_id and product_id=:product_id");
        $stmt->execute(['product_id' => $productId, 'user_id' => $userId]);
    }
    protected function getTableName():string
    {
        return "user_products";
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
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