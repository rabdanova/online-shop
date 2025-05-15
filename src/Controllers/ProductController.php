<?php

class ProductController
{
    public function getAddProductForm()
    {
        require_once '../Views/add-product.php';
    }

    public function catalog()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        require_once "../Model/Product.php";
        $productModel = new Product();
        $products = $productModel->getAllProducts();

        require_once '../Views/catalog.php';
    }

    public function addProduct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ./login.php');
            exit;
        }

        $errors = $this->Validate($_POST);

        if (empty($errors)) {
            $amount = $_POST["amount"];
            $productId = $_POST["product_id"];
            $userId = $_SESSION["user_id"];

            require_once "../Model/Product.php";
            $productModel = new Product();
            $result = $productModel->getByTwoId($userId, $productId);

            if ($result === false || empty($result)) {
                $productModel->insertById($userId, $productId, $amount);
            } else {
                $newAmount = $result["amount"] + $amount;
                $productModel->updateById($userId, $productId, $newAmount);
            }

            header("Location: catalog");

        } else {
            require_once '../Views/add-product.php';
        }
    }

    private function Validate(array $data): array
    {
        $errors = [];

        $errorProductId = $this->ValidateProductId($data);
        if (!empty($errorProductId)) {
            $errors['product_id'] = $errorProductId;
        }

        $errorAmount = $this->ValidateAmount($data);
        if (!empty($errorAmount)) {
            $errors['amount'] = $errorAmount;
        }

        return $errors;
    }

    private function ValidateProductId($data): string|null
    {
        if (isset($data['product_id'])) {
            $productId = $data['product_id'];

            if (is_numeric($productId)) {
                require_once "../Model/Product.php";
                $productModel = new Product();
                $result = $productModel->getByProductId($productId);

                if ($result === false) {
                    return "Продукта с таким id не существует";
                } else {
                    return NUll;
                }
            } else {
                return "Неправильный формат id";
            }
        } else {
            return 'Введите id продукта';
        }
    }

    private function ValidateAmount($data): string|null
    {
        if (isset($data['amount'])) {
            $amount = $data['amount'];

            if (!is_numeric($amount)) {
                return 'Введите число';
            } else {
                if ($amount > 0) {
                    return null;
                } else {
                    return 'Введите положительное число';
                }
            }
        } else {
            return 'Введите количество желаемого товара';
        }
    }
}

