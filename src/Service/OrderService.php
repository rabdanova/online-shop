<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class OrderService
{
    private UserProduct $userProductModel;
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private AuthInterface $authService;
    private Product $productModel;

    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->authService = new AuthSessionService();
        $this->productModel = new Product();
    }
    public function createOrder(OrderCreateDTO $data)
    {
        $user = $this->authService->getCurrentUser();

        $orderId = $this->orderModel->create(
            $data->getName(),
            $data->getPhoneNumber(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId());

        $userProducts = $this->userProductModel->getByUserId($user->getId());

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();

            $this->orderProductModel->create($orderId, $productId, $amount);
        }

        $this->userProductModel->deleteByUserId($user->getId());
    }

    public function getAll()
    {
        $user = $this->authService->getCurrentUser();
        $userOrders = $this->orderModel->getByUserId($user->getId());

        foreach ($userOrders as $userOrder) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder->getId());
            $sum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getById($orderProduct->getProductId());
                $orderProduct->setName($product->getName());
                $orderProduct->setPrice($product->getPrice());
                $orderProduct->setImageUrl($product->getImageUrl());
                $orderProduct->setTotalSum($orderProduct->getAmount() * $product->getPrice());


                $sum = $sum + $orderProduct->getTotalSum();
            }
            $userOrder->setTotal($sum);
            $userOrder->setProducts($orderProducts);
        }

        return $userOrders;
    }
}