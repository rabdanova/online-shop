<?php

namespace DTO;

use Model\User;
use Model\UserProduct;

class CartDTO
{
    public function __construct(private int $productId){

    }

    public function getProductId(): int
    {
        return $this->productId;
    }



}