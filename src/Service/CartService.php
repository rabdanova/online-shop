<?php

namespace Service;

use DTO\CartDTO;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private UserProduct $userProductModel;
    private AuthInterface $authService;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->authService = new AuthSessionService();
    }
    public function addProduct(CartDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $amount = 1;
        $result = $this->userProductModel->getOneByProductAndUserId($user->getId(), $data->getProductId());

        if (empty($result)) {
            $this->userProductModel->insertById($user->getId(), $data->getProductId(), $amount);
        } else {
            $newAmount = $result->getAmount() + $amount;
            $this->userProductModel->updateById($user->getId(), $data->getProductId(), $newAmount);
        }
    }

    public function decreaseProduct(CartDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $amount = 1;
        $result = $this->userProductModel->getOneByProductAndUserId($user->getId(), $data->getProductId());

        if (!empty($result)) {
            $newAmount = $result->getAmount() - $amount;
            if ($newAmount >= 1 )
            {
                $this->userProductModel->updateById($user->getId(), $data->getProductId(), $newAmount);
            } else
            {
                $this->userProductModel->deleteOneByProductAndUserId($data->getProductId(),$user->getId());
            }
        }
    }
}