<?php

namespace DTO;

use Model\User;
use Model\UserProduct;

class CartDTO
{
    public function __construct(private User $user, private int $productId){

    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUser(): User
    {
        return $this->user;
    }


}