<?php

namespace DTO;

use Model\UserProduct;

class CartDTO
{
    public function __construct(private int $productId, private int $userId){

    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }


}