<?php

namespace Model;

class Review extends Model
{
    private string $comment;
    private int $rating;

    public function create(string $comment, int $productId, int $userId, int $rating)
    {
        $stmt = $this->getPDO()->prepare("INSERT INTO {$this->getTableName()} (
                user_id, product_id, comment, rating) VALUES (:user_id, :product_id, :comment, :rating)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'comment' => $comment, 'rating' => $rating]);

    }
    public function getAllByProductId($productId):array|null
    {
        $stmt = $this->getPDO()->prepare("select * from  {$this->getTableName()} where product_id=:product_id");
        $stmt->execute(['product_id' => $productId]);
        $reviews = $stmt->fetchAll();

        $arr = [];
        foreach ($reviews as $review) {
            if (!$review) {
                return null;
            }
            $obj = new self();
            $obj->comment = $review["comment"];
            $obj->rating = $review["rating"];
            $arr[] = $obj;
        }
        return $arr;
    }
    protected function getTableName():string
    {
        return "reviews";
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getRating(): int
    {
        return $this->rating;
    }


}