<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\UserProduct;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Service\AuthService;
use Service\OrderService;

class OrderController extends BaseController
{
    private UserProduct $userProductModel;
    private Order $orderModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;
    private OrderService $orderService;
    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
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
    public function handleCheckout(array $data)
    {
        if ($this->authService->check()) {
            $errors = $this->validateOrder($data);
            $user = $this->authService->getCurrentUser();

            if (empty($errors)) {
                $dto = new OrderCreateDTO($data['name'], $data['phone_number'],$data['comment'], $data['address'], $user);
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

        $userOrders = $this->orderModel->getByUserId($user->getId());

        $newUserOrders = [];


        foreach ($userOrders as $userOrder) {

            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder->getId());
            $newOrderProducts = [];
            $sum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getById($orderProduct->getProductId());
                $orderProduct->setName($product->getName());
                $orderProduct->setPrice($product->getPrice());
                $orderProduct->setImageUrl($product->getImageUrl());
                $orderProduct->setTotalSum($orderProduct->getAmount() * $product->getPrice());
                $newOrderProducts[] = $orderProduct;

                $sum = $sum + $orderProduct->getTotalSum();
            }
            $userOrder->setTotal($sum);
            $userOrder->setProducts($newOrderProducts);
            $newUserOrders[] = $userOrder;
        }

        require_once "../Views/UserOrders_form.php";
    }

    private function validateOrder($data): array
    {
        $errors = [];

        $errorName = $this->validateName($data);
        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }

        $errorPhoneNumber = $this->validatePhoneNumber($data);
        if (!empty($errorPhoneNumber)) {
            $errors['phone_number'] = $errorPhoneNumber;
        }

        $errorAddress = $this->validateAddress($data);
        if (!empty($errorAddress)) {
            $errors['address'] = $errorAddress;
        }

        return $errors;
    }

    private function validateName(array $data): null|string
    {
        if (isset($data['name'])) {
            $name = $data['name'];
            if ((strlen($name) <= 3)) {
                return 'Недопустимая длина имени';
            } else {
                return NULL;
            }

        } else {
            return 'Введите имя пользователя';
        }
    }

    private function validatePhoneNumber(array $data): null|string
    {
        if (isset($data['phone_number'])) {
            $phone = $data['phone_number'];

            if (!is_numeric($phone)) {
                return 'Введите число';
            } else {
                if ($phone > 0) {
                    return null;
                } else {
                    return 'Введите положительное число';
                }
            }
        } else {
            return 'Введите количество желаемого товара';
        }
    }

    private function validateAddress(array $data): null|string
    {
        if (isset($data['address'])) {
            $address = $data['address'];

            if (is_numeric($address)) {
                return 'Некорректный формат названия города';
            } else {
                return NULL;
            }
        }

        return 'Введите название населенного пункта';
    }

}