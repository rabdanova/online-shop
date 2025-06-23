<?php

namespace Service;

use DTO\CartDTO;
use Model\UserProduct;

class CartService
{
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(CartDTO $data)
    {
        $amount = 1;
        $result = $this->userProductModel->getOneByProductAndUserId($data->getUserId(), $data->getProductId());

        if (empty($result)) {
            $this->userProductModel->insertById($data->getUserId(), $data->getProductId(), $amount);
        } else {
            $newAmount = $result->getAmount() + $amount;
            $this->userProductModel->updateById($data->getUserId(), $data->getProductId(), $newAmount);
        }
    }

    public function decreaseProduct(CartDTO $data)
    {
        $amount = 1;
        $result = $this->userProductModel->getOneByProductAndUserId($data->getUserId(), $data->getProductId());

        if (!empty($result)) {
            $newAmount = $result->getAmount() - $amount;
            if ($newAmount >= 1 )
            {
                $this->userProductModel->updateById($data->getUserId(), $data->getProductId(), $newAmount);
            } else
            {
                $this->userProductModel->deleteOneByProductAndUserId($data->getUserId(), $data->getProductId());
            }
        }
    }
}