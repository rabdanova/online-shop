<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\Product;
use Model\UserProduct;
use Request\CreateOrderRequest;
use Service\OrderService;

class OrderController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderService = new OrderService();

    }

    public function getCheckoutForm()
    {
        if (!$this->authService->check()) {
            header('Location: ./login.php');
            exit;
        }

        $user = $this->authService->getCurrentUser();

        $userProducts = $this->userProductModel->getByUserId($user->getId());

        $userProductsForOrder = [];
        $totalSum = 0;

        foreach ($userProducts as $cartProduct) {
            $productId = $cartProduct->getProductId();
            $amount = $cartProduct->getAmount();

            $product = $this->productModel->getById($productId);

            if (!empty($product)) {
                $price = $product->getPrice();
                $name = $product->getName();
                $sum = $price * $amount;
                $totalSum += $sum;

                $userProductsForOrder[] = [
                    'product_id' => $productId,
                    'name' => $name,
                    'price' => $price,
                    'amount' => $amount,
                    'sum' => $sum
                ];
            }
        }
        require_once "./../Views/Order_form.php";
    }
    public function handleCheckout(CreateOrderRequest $request)
    {
        if ($this->authService->check()) {
            $errors = $request->validateOrder();

            if (empty($errors)) {
                $dto = new OrderCreateDTO($request->getName(), $request->getPhoneNumber(),$request->getComment(), $request->getAddress());
                $this->orderService->createOrder($dto);
                header("location: /catalog");
            }  else {
                print_r($errors);
            }
        } else {
            header('Location: ./login.php');
            exit;
        }
    }

    public function getUserOrders(){

        if (!$this->authService->check()) {
            header('Location: ./login.php');
            exit;
        }
        $user = $this->authService->getCurrentUser();

        $userOrders = $this->orderService->getAll();

        require_once "../Views/UserOrders_form.php";
    }



}