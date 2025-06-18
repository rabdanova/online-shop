<?php

namespace Model;

class Order extends Model
{
    private int $id;
    private string $name;
    private string $phoneNumber;
    private string $userId;
    private string $address;
    private string $comment;
    private int $total;
    private array $products;
    public function create(string $name, string $phone, string $address, string $comment, int $userId)
    {
        $stmt = $this->getPDO()->prepare("INSERT INTO {$this->getTableName()} (
                    name, phone_number, address, comment, user_id) VALUES (:name, :phone_number, :address, :comment, :user_id) RETURNING id");
        $stmt->execute(['name' => $name, 'phone_number' => $phone, 'address' => $address, 'comment' => $comment, 'user_id' => $userId]);

        $data = $stmt->fetch();

        return $data['id'];
    }

    public function getByUserId($userId) :array|null
    {
        $stmt = $this->getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $userOrders = $stmt->fetchAll();
        $arr = [];
        foreach ($userOrders as $item) {
            if (!$item) {
                return null;
            }
            $obj = new self();
            $obj->id = $item["id"];
            $obj->userId = $item["user_id"];
            $obj->name = $item["name"];
            $obj->address = $item["address"];
            $obj->phoneNumber = $item["phone_number"];
            $obj->comment = $item["comment"];

            $arr[] = $obj;
        }
        return $arr;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    protected function getTableName():string
    {
        return "orders";
    }
}