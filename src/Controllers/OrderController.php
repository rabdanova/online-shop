<?php

namespace Controllers;

use Model\UserProduct;
use Model\Order;
use Model\OrderProduct;
use Model\Product;

class OrderController
{
    private UserProduct $userProductModel;
    private Order $orderModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;

    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
    }

    public function getCheckoutForm()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ./login.php');
            exit;
        }

        $userId = $_SESSION["user_id"];

        $userProducts = $this->userProductModel->getByUserId($userId);

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
    public function handleCheckout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ./login.php');
            exit;
        }

        $errors = $this->validateOrder($_POST);

        if (empty($errors)) {
            $name = $_POST["name"];
            $phone = $_POST["phone_number"];
            $address = $_POST["address"];
            $comment = $_POST["comment"];
            $userId = $_SESSION["user_id"];

            $orderId = $this->orderModel->create($name, $phone, $address, $comment, $userId);

            $userProducts = $this->userProductModel->getByUserId($userId);

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            $this->userProductModel->deleteByUserId($userId);

            header("location: /catalog");
        } else {
            print_r($errors);
        }
    }

    public function getUserOrders(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ./login.php');
            exit;
        }
        $userId = $_SESSION["user_id"];

        $userOrders = $this->orderModel->getByUserId($userId);

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
//        echo "<pre>";
//        print_r($newUserOrders);
//        exit;
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