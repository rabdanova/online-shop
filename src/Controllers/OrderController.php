<?php

namespace Controllers;

use Model\UserProduct;
use Model\Order;
use Model\OrderProduct;
use Model\Product;

class OrderController
{
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

        $userProductModel = new UserProduct();
        $userProducts = $userProductModel->getByUserId($userId);
        $ProductModel = new Product();

        $userProductsForOrder = [];
        $totalSum = 0;

        foreach ($userProducts as $cartProduct) {
            $productId = $cartProduct["product_id"];
            $amount = $cartProduct["amount"];

            $product = $ProductModel->getById($productId);

            if (!empty($product)) {
                $price = $product['price'];
                $name = $product['name'];
                $sum = $price * $amount;
                $totalSum += $sum;

                $userProductsForOrder[] = [
                    'product_id' => $productId,
                    'name' => $name,
                    'price' => $price,
                    'amount' => $amount,
                    'sum' => $sum
                ];
//                echo '<pre>';
//                print_r($userProductsForOrder);
//                exit;

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

            $order = new Order();
            $orderId = $order->create($name, $phone, $address, $comment, $userId);

            $cart = new UserProduct();
            $userProducts = $cart->getByUserId($userId);

            $orderProduct = new OrderProduct();
            foreach ($userProducts as $userProduct) {
                $productId = $userProduct["product_id"];
                $amount = $userProduct["amount"];

                $orderProduct->create($orderId, $productId, $amount);
            }

            $cart->deleteByUserId($userId);

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

        $orderModel = new Order();
        $orderProductModel = new OrderProduct();
        $ProductModel = new Product();

        $userOrders = $orderModel->getByUserId($userId);

        foreach ($userOrders as $userOrder) {

            $orderProducts = $orderProductModel->getOrderProducts($userOrder["id"]);
            $products = [];
            $totalSum = 0;

            foreach ($orderProducts as $orderProduct) {
                $product = $ProductModel->getById($orderProduct["product_id"]);
                if (!empty($product)) {
                    $sum = $product["price"] * $orderProduct["amount"];
                    $totalSum += $sum;

                    $products[] = [
                        'name' => $product["name"],
                        'price' => $product["price"],
                        'amount' => $orderProduct["amount"],
                        'sum' => $sum
                    ];
                }
            }
            $userOrderProducts[] = [
                'order' => $userOrder,
                'products' => $products,
                'totalSum' => $totalSum
            ];
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