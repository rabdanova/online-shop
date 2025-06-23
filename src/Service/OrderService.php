<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\UserProduct;

class OrderService
{
    private UserProduct $userProductModel;
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
    }
    public function createOrder(OrderCreateDTO $data)
    {
        $orderId = $this->orderModel->create(
            $data->getName(),
            $data->getPhoneNumber(),
            $data->getComment(),
            $data->getAddress(),
            $data->getUser()->getId());

        $userProducts = $this->userProductModel->getByUserId($data->getUser()->getId());

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();

            $this->orderProductModel->create($orderId, $productId, $amount);
        }

        $this->userProductModel->deleteByUserId($data->getUser()->getId());
    }
}